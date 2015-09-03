<?php


defined('INTERNAL') || die();

class PluginBlocktypeAllfacts extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.milestones/allfacts');
    }

    public static function get_description() {
        return get_string('description1', 'blocktype.milestones/allfacts');
    }

    public static function get_categories() {
        return array('general' => 22000);
    }

     /**
     * Optional method. If exists, allows this class to decide the title for
     * all blockinstances of this type
     */
    public static function get_instance_title(BlockInstance $bi) {
        $configdata = $bi->get('configdata');

        if (!empty($configdata['artefactid'])) {
            return $bi->get_artefact_instance($configdata['artefactid'])->get('title');
        }
        return '';
    }

    public static function get_instance_javascript(BlockInstance $bi) {
        $blockid = $bi->get('id');
        return array(
            array(
                'file'   => 'js/milestonesblock.js',
                'initjs' => "initNewMilestonesBlock($blockid);",
            )
        );
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        global $exporter;

        require_once(get_config('docroot') . 'artefact/lib.php');
        safe_require('artefact','milestones');

        $configdata = $instance->get('configdata');

        $smarty = smarty_core();
        $blockid = param_integer('block', '');
        $this_instance_id = $instance->get('id');
        if ((!$blockid) or $blockid and $blockid == $this_instance_id) {
            $offset = param_integer('offset', 0);
            $limit = param_integer('limit', 3);
        } else {
            $offset = 0;
            $limit = 3;
            $blockid = $this_instance_id;
        }
        $facts = ArtefactTypeFact::get_allfacts($blockid, $offset, $limit);
        $template = 'artefact:milestones:allfactrows.tpl';
        if ($exporter) {
            $pagination = false;
        }
        else {
            $baseurl = $instance->get_view()->get_url();
            $baseurl .= ((false === strpos($baseurl, '?')) ? '?' : '&') . 'block=' . $blockid;
            $pagination = array(
                'baseurl'   => $baseurl,
                'id'        => 'block' . $blockid . '_pagination',
                'datatable' => 'facttable_' . $blockid,
                'jsonscript' => 'artefact/milestones/viewfacts.json.php',
            );
        }
        ArtefactTypeFact::render_facts($facts, $template, $configdata, $pagination);

        if ($exporter && $facts['count'] > $facts['limit']) {
            $artefacturl = get_config('wwwroot') . 'artefact/artefact.php?artefact=' . $configdata['artefactid']
                . '&view=' . $instance->get('view');
            $facts['pagination'] = '<a href="' . $artefacturl . '">' . get_string('allfacts', 'artefact.milestones') . '</a>';
        }
        $smarty->assign('facts', $facts);
        $smarty->assign('blockid', $instance->get('id'));
        return $smarty->fetch('blocktype:allfacts:content.tpl');

    }

    public static function has_instance_config() {
        return false;
    }

    public static function instance_config_form(BlockInstance $instance) {
        $instance->set('artefactplugin', 'milestones');

        $form = array();
        return $form;
    }

    public static function artefactchooser_element($default=null) {
    }

    public static function allowed_in_view(View $view) {
        return $view->get('owner') != null;
    }
}
