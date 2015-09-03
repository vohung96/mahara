<?php
/**
 *
 * @package    mahara
 * @subpackage blocktype-profileinfo
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

defined('INTERNAL') || die();

class PluginBlocktypeEselmagroup extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.internal/eselmagroup');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.internal/eselmagroup');
    }

    public static function get_categories() {
        return array('internal' => 26500);
    }

    public static function get_instance_config_javascript(BlockInstance $instance) {
        return array('js/configform.js');
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        require_once(get_config('docroot') . 'artefact/lib.php');
        global $USER;
        $smarty = smarty_core();

        $groupdata = group_get_associated_groups($USER->get('id'), 'all', null, null);
        $data = new stdclass();
        $data->data = array();
        $data->count = $groupdata['count'];
        $data->displayname = display_name($user);
        if ($data->count) {
            foreach ($groupdata['groups'] as $g) {
                $record = array();
                $record['id'] = $g->id;
                $record['name'] = $g->name;
                $record['description'] = $g->description;
                $owner = group_get_admin_ids($g->id);
                $record['group_admin'] = display_name($owner[0]);
                $data->data[] = $record;
            }
        }
        $smarty->assign('eselma_groups', $data);
        return $smarty->fetch('blocktype:eselmagroup:content.tpl');
    }

    public static function artefactchooser_element($default=null) {
    }

}
