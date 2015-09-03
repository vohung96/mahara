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

class PluginBlocktypeProfileicon extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.internal/profileicon');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.internal/profileicon');
    }

    public static function get_categories() {
        return array('internal' => 26500);
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        global $USER;
        $loggedinid = $USER->get('id');
        $viewowner = get_field('view', 'owner', 'id', $instance->get('view'));
        if ($loggedinid && $loggedinid == $viewowner) {
            $ownprofile = true;
        } else {
            $ownprofile = false;
        }

        $smarty = smarty_core();
        $smarty->assign('ownprofile', $ownprofile);
        $smarty->assign('user', $viewowner);

        return $smarty->fetch('blocktype:profileicon:content.tpl');
    }

    public static function artefactchooser_element($default=null) {
    }

}
