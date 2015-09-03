<?php

defined('INTERNAL') || die();

/**
 * PDF export plugin
 *
 * General notes:
 *
 * Returns a .pdf file rather than a zip file like other export plugins
 */
class PluginExportPdf extends PluginExport {

    /**
     * The name of resultant pdf file
     */
    protected $pdffile;

    /**
     * The name of the directory under which all the other directories and 
     * files will be placed in the export
     */
    protected $rootdir;

    /**
     * List of stylesheets to included for the HTML temporary file.
     */
    private $stylesheets = array('' => array());

    /**
     * constructor.  overrides the parent class
     * to set up smarty and the attachment directory
     */
    public function __construct(User $user, $views, $artefacts, $progresscallback = null) {
        global $THEME;
        parent::__construct($user, $views, $artefacts, $progresscallback);

        $this->rootdir = 'portfolio-for-' . self::text_to_path($user->get('username'));
        $directory = "{$this->exportdir}/{$this->rootdir}/";
        if (!check_dir_exists($directory)) {
            throw new SystemException("Couldn't create the temporary export directory $directory");
        }
        $this->pdffile = 'mahara-export-pdf-user'
                . $this->get('user')->get('id') . '-' . $this->exporttime . '.pdf';

        // Find what base stylesheets need to be included
        $themedirs = $THEME->get_path('', true);
        $stylesheets = array('print.css', 'views.css');
        foreach ($themedirs as $theme => $themedir) {
            foreach ($stylesheets as $stylesheet) {
                if (is_readable($themedir . 'style/' . $stylesheet)) {
                    array_unshift($this->stylesheets[''], $themedir . '/style/' . $stylesheet);
                }
            }
        }

        // Don't export the dashboard
//        foreach (array_keys($this->views) as $i) {
//            if ($this->views[$i]->get('type') == 'dashboard') {
//                unset($this->views[$i]);
//            }
//        }

        $this->exportingoneview = (
                $this->viewexportmode == PluginExport::EXPORT_LIST_OF_VIEWS &&
                $this->artefactexportmode == PluginExport::EXPORT_ARTEFACTS_FOR_VIEWS &&
                count($this->views) == 1
                );

        $this->notify_progress_callback(15, get_string('setupcomplete', 'export'));
    }

    public static function get_title() {
        return get_string('title', 'export.pdf');
    }

    public static function get_description() {
        return get_string('description', 'export.pdf');
    }

    /**
     * Main export routine
     */
    public function export() {
        global $THEME;
        raise_memory_limit('128M');

        $this->notify_progress_callback(90, get_string('creatingpdffile', 'export.pdf'));

        $this->create_html();
        $this->create_pdf();

        $this->notify_progress_callback(100, get_string('Done', 'export'));

        return $this->pdffile;
    }

    public function cleanup() {
        // @todo remove temporary files and directories
    }

    /**
     * Shared template settings for all exported views
     */
    public function get_smarty() {
        $stylesheets = array(
            '<link rel="stylesheet" type="text/css" href="' . get_config('wwwroot') . 'theme/views.css">',
            '<link rel="stylesheet" type="text/css" href="' . get_config('wwwroot') . 'theme/raw/static/style/print.css">',
            '<link rel="stylesheet" type="text/css" href="' . get_config('wwwroot') . 'theme/raw/static/style/style.css">',
            '<link rel="stylesheet" type="text/css" href="' . get_config('wwwroot') . 'theme/raw/static/style/views.css">',
            '<link rel="stylesheet" type="text/css" href="' . get_config('wwwroot') . 'theme/themecustom/static/style/print.css">',
            '<link rel="stylesheet" type="text/css" href="' . get_config('wwwroot') . 'theme/themecustom/static/style/style.css">',
            '<link rel="stylesheet" type="text/css" href="' . get_config('wwwroot') . 'theme/themecustom/static/style/views.css">'
        );
        $smarty = smarty(
                array(), $stylesheets, array(), array(
            'stylesheets' => array('style/views.css'),
            'sidebars' => false,
                )
        );
        if (get_config('viewmicroheaders')) {
            $smarty->assign('microheaders', true);
        }
        $user = $this->get('user');
        $exporttime = format_date($this->exporttime, 'strftimedatetimeshort');
        $smarty->assign('generatedfor', get_string('exportgeneratedfor', 'export.pdf', display_name($user, null, true), $exporttime, get_config('sitename')));
        return $smarty;
    }

    /**
     * Dumps all views into the PDF ready HTML format
     */
    private function create_html() {
        global $THEME;

        $progressstart = 55;
        $progressend = 75;
        $i = 0;
        $viewcount = count($this->views);

        // multiple views append header with standard theme
        if (!$this->exportingoneview) {
            $smarty = $this->get_smarty();
            $header = $smarty->fetch('export:pdf:head.tpl');
            if (!file_put_contents("{$this->exportdir}/{$this->rootdir}/" . "index.html", $header, FILE_APPEND | LOCK_EX)) {
                throw new SystemException("Could not write view page for pdf export");
            }
        }

        foreach ($this->views as $id => $view) {
            $this->notify_progress_callback(
                    intval($progressstart + ( ++$i / $viewcount) * ($progressend - $progressstart)), get_string('exportingviewsprogress', 'export', $i, $viewcount)
            );

            // Set up view theme if we're just exporting a single view
            if (!isset($smarty) && $this->exportingoneview) {
                $viewtheme = $view->get('theme');
                if ($viewtheme && $THEME->basename != $viewtheme) {
                    $THEME = new Theme($viewtheme);
                }
                $smarty = $this->get_smarty();
                $header = $smarty->fetch('export:pdf:head.tpl');
                if (!file_put_contents("{$this->exportdir}/{$this->rootdir}/" . "index.html", $header, FILE_APPEND | LOCK_EX)) {
                    throw new SystemException("Could not write view page for pdf export");
                }
            }

            $owner = $view->get('owner');
            $viewtype = $view->get('type');

            if (get_config('viewmicroheaders')) {
                $smarty->assign('microheadertitle', $view->display_title(true, false));
            }

            // fetch the html for a single view
            $smarty->assign('viewtitle', $view->get('title'));
            $smarty->assign('ownername', $view->formatted_owner());
            $smarty->assign('viewdescription', ArtefactTypeFolder::append_view_url($view->get('description'), $view->get('id')));
            $smarty->assign('viewcontent', $view->build_rows(false, true));
            $smarty->assign('tags', $view->get('tags'));
            $viewcontent = $smarty->fetch('export:pdf:view.tpl');

            // include a pagebreak into the pdf if we are exporting multiple views
            if (!$this->exportingoneview && $i < $viewcount) {
                $viewcontent .= $smarty->fetch('export:pdf:pagebreak.tpl');
            }

            // append any new views to the end of the main html for a bulk export
            if (!file_put_contents("{$this->exportdir}/{$this->rootdir}/" . "index.html", $viewcontent, FILE_APPEND | LOCK_EX)) {
                throw new SystemException("Could not write view page for pdf export");
            }
        }
        // append footer
        $footer = $smarty->fetch('export:pdf:foot.tpl');
        if (!file_put_contents("{$this->exportdir}/{$this->rootdir}/" . "index.html", $footer, FILE_APPEND | LOCK_EX)) {
            throw new SystemException("Could not write view page for pdf export");
        }
    }

    /**
     * Converts HTML Views into a single PDF document
     */
    private function create_pdf() {
        raise_memory_limit('128M');

        if (is_readable($this->exportdir . $this->rootdir . "/index.html")) {
            $sourcefile = $this->exportdir . $this->rootdir . "/index.html";
        }
        $targetfile = $this->exportdir . $this->pdffile;

        $output = array();
//        $wkhtmltopdfpath = get_config_plugin('export','pdf','pathtowkhtmltopdf');
//        $xvfbrunpath = get_config_plugin('export','pdf','pathtoxvfbrun');
        $wkhtmltopdfpath = get_config(pathtowkhtmltopdf);
        $xvfbrunpath = get_config(pathtoxvfbrun);
        $command = sprintf('%s %s %s %s %s', $xvfbrunpath, $wkhtmltopdfpath, '-O landscape', $sourcefile, $targetfile
        );
        log_debug($command);

        exec($command, $output, $returnvar);
        if ($returnvar != 0) {
            throw new SystemException('Failed to create the pdf file');
        }

        return;
    }

    /**
     * Converts the passed text into a a form that could be used in a URL.
     *
     * @param string $text The text to convert
     * @return string      The converted text
     */
    public static function text_to_path($text) {
        return substr(preg_replace('#[^a-zA-Z0-9_-]+#', '-', $text), 0, 255);
    }

    public static function has_config() {
        return true;
    }

    public function is_diskspace_available() {
        return true;
    }

}

?>
