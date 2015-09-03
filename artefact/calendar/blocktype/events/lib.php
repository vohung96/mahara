<?php
/**
 *
 * @package    mahara
 * @subpackage blocktype-events
 * @author     NextG Solutions
 *
 */

defined('INTERNAL') || die();

class PluginBlocktypeEvents extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.calendar/events');
    }

    public static function get_description() {
        return get_string('description1', 'blocktype.calendar/events');
    }

    public static function get_categories() {
        return array('general' => 21500);
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
                'file'   => 'js/eventsblock.js',
                'initjs' => "initNewEventsBlock($blockid);",
            )
        );
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        global $exporter;

        require_once(get_config('docroot') . 'artefact/lib.php');
        safe_require('artefact','calendar');

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
        $events = ArtefactTypeEvent::get_all_events($blockid, $offset, $limit);
        
        $template = 'artefact:calendar:eventrows.tpl';
        if ($exporter) {
            $pagination = false;
        }
        else {
            $baseurl = $instance->get_view()->get_url();
            $baseurl .= ((false === strpos($baseurl, '?')) ? '?' : '&') . 'block=' . $blockid;
            $pagination = array(
                'baseurl'   => $baseurl,
                'id'        => 'block' . $blockid . '_pagination',
                'datatable' => 'eventtable_' . $blockid,
                'jsonscript' => 'artefact/calendar/viewevents.json.php',
            );
        }
        ArtefactTypeEvent::render_events($events, $template, $pagination);

        if ($exporter && $events['count'] > $events['limit']) {
            $artefacturl = get_config('wwwroot') . 'artefact/artefact.php?artefact=' . $blockid
                . '&view=' . $instance->get('view');
            $events['pagination'] = '<a href="' . $artefacturl . '">' . get_string('allevents', 'artefact.calendar') . '</a>';
        }
        $smarty->assign('events', $events);
        $smarty->assign('blockid', $blockid);
        return $smarty->fetch('blocktype:events:content.tpl');
    }

    // My Events blocktype only has 'title' option so next two functions return as normal
    public static function has_instance_config() {
        return false;
    }

    public static function instance_config_form(BlockInstance $instance) {
        $instance->set('artefactplugin', 'calendar');

        $form = array();
        return $form;
    }

    public static function artefactchooser_element($default=null) {
    }

    public static function allowed_in_view(View $view) {
        return $view->get('owner') != null;
    }
}
