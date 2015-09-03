<?php

defined('INTERNAL') || die();

class PluginBlocktypeMilestones extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.milestones/milestones');
    }

    public static function get_description() {
        return get_string('description1', 'blocktype.milestones/milestones');
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
        if (isset($configdata['artefactid'])) {
            $milestone = artefact_instance_from_id($configdata['artefactid']);
            $facts = ArtefactTypeFact::get_facts($configdata['artefactid']);
            $template = 'artefact:milestones:factrows.tpl';
            $blockid = $instance->get('id');
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
            $smarty->assign('description', $milestone->get('description'));
            $smarty->assign('owner', $milestone->get('owner'));
            $smarty->assign('tags', $milestone->get('tags'));
            $smarty->assign('facts', $facts);
        }
        else {
            $smarty->assign('nomilestones','blocktype.milestones/milestones');
        }
        $smarty->assign('blockid', $instance->get('id'));
        return $smarty->fetch('blocktype:milestones:content.tpl');
    }


    public static function has_instance_config() {
        return true;
    }

    public static function instance_config_form(BlockInstance $instance) {
        $instance->set('artefactplugin', 'milestones');
        $configdata = $instance->get('configdata');

        $form = array();

        // Which resume field does the user want
        $form[] = self::artefactchooser_element((isset($configdata['artefactid'])) ? $configdata['artefactid'] : null);

        return $form;
    }

    public static function artefactchooser_element($default=null) {
        safe_require('artefact', 'milestones');
        return array(
            'name'  => 'artefactid',
            'type'  => 'artefactchooser',
            'title' => get_string('milestonestoshow', 'blocktype.milestones/milestones'),
            'defaultvalue' => $default,
            'blocktype' => 'milestones',
            'selectone' => true,
            'search'    => false,
            'artefacttypes' => array('milestone'),
            'template'  => 'artefact:milestones:artefactchooser-element.tpl',
        );
    }

    public static function allowed_in_view(View $view) {
        return $view->get('owner') != null;
    }
}
