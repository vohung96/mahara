<?php
/**
 *
 * @package    mahara
 * @subpackage blocktype-eselmamanagecontact
 *
 */

defined('INTERNAL') || die();

class PluginBlocktypeEselmamanagecontact extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.internal/eselmamanagecontact');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.internal/eselmamanagecontact');
    }

    public static function get_categories() {
        return array('internal' => 27500);
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        require_once(get_config('docroot') . 'artefact/lib.php');
        global $USER;
        $smarty = smarty_core();

        $search_friend_title = get_string('search_friend', 'blocktype.internal/eselmamanagecontact');
        $user_control_options = array();
        $user_control_options['nobody'] = get_string('friendsnobody', 'account');
        $user_control_options['auth'] = get_string('friendsauth', 'account');
        $user_control_options['auto'] = get_string('friendsauto', 'account');
        $current_user_control_options = $USER->get_account_preference('friendscontrol');
        
        $smarty->assign('search_friend_title', $search_friend_title);
        $smarty->assign('current_user_control_options', $current_user_control_options);
        $smarty->assign('user_control_options', $user_control_options);
        return $smarty->fetch('blocktype:eselmamanagecontact:content.tpl');
    }

    public static function artefactchooser_element($default=null) {
    }

}
