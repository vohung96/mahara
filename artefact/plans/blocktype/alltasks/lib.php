<?php
/**
 *
 * @package    mahara
 * @subpackage blocktype-plans
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

defined('INTERNAL') || die();

class PluginBlocktypeAlltasks extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.plans/alltasks');
    }

    public static function get_description() {
        return get_string('description1', 'blocktype.plans/alltasks');
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
                'file'   => 'js/plansblock.js',
                'initjs' => "initNewPlansBlock($blockid);",
            )
        );
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        global $exporter;

        require_once(get_config('docroot') . 'artefact/lib.php');
        safe_require('artefact','plans');

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
        $tasks = ArtefactTypeTask::get_alltasks($blockid, $offset, $limit);
        $template = 'artefact:plans:alltaskrows.tpl';
        if ($exporter) {
            $pagination = false;
        }
        else {
            $baseurl = $instance->get_view()->get_url();
            $baseurl .= ((false === strpos($baseurl, '?')) ? '?' : '&') . 'block=' . $blockid;
            $pagination = array(
                'baseurl'   => $baseurl,
                'id'        => 'block' . $blockid . '_pagination',
                'datatable' => 'tasktable_' . $blockid,
                'jsonscript' => 'artefact/plans/viewtasks.json.php',
            );
        }
        ArtefactTypeTask::render_tasks($tasks, $template, $configdata, $pagination);

        if ($exporter && $tasks['count'] > $tasks['limit']) {
            $artefacturl = get_config('wwwroot') . 'artefact/artefact.php?artefact=' . $configdata['artefactid']
                . '&view=' . $instance->get('view');
            $tasks['pagination'] = '<a href="' . $artefacturl . '">' . get_string('alltasks', 'artefact.plans') . '</a>';
        }
        $smarty->assign('tasks', $tasks);
        $smarty->assign('blockid', $instance->get('id'));
        return $smarty->fetch('blocktype:alltasks:content.tpl');

    }

    // My Plans blocktype only has 'title' option so next two functions return as normal
    public static function has_instance_config() {
        return false;
    }

    public static function instance_config_form(BlockInstance $instance) {
        $instance->set('artefactplugin', 'plans');

        $form = array();
        return $form;
    }

    public static function artefactchooser_element($default=null) {
    }

    public static function allowed_in_view(View $view) {
        return $view->get('owner') != null;
    }
}
