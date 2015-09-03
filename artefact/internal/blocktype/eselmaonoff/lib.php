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

class PluginBlocktypeEselmaonoff extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.internal/eselmaonoff');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.internal/eselmaonoff');
    }

    public static function get_categories() {
        return array('internal' => 26000);
    }

    public static function get_instance_config_javascript(BlockInstance $instance) {
        return array('js/configform.js');
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        require_once(get_config('docroot') . 'artefact/lib.php');
        $smarty = smarty_core();
        
        $filter = param_alpha('filter', 'all');
        $offset = param_integer('offset', 0);
        $limit = 1000;

        //get list online friend
        $result_friend_online_id = get_onlinefriends($limit, $offset);
        $str_eselma_online = implode(',', $result_friend_online_id['data']);
        if($str_eselma_online){
            $query_result_friend_online = "
                SELECT *
                FROM {usr}
                WHERE id in ($str_eselma_online)
            ";
            $result_friend_online = get_records_sql_array($query_result_friend_online);
        }

        //get list offline friend
        $result_friend_offline_id = get_offlinefriends($limit , $offset);
        $str_eselma_offline = implode(',', $result_friend_offline_id['data']);
        if($str_eselma_offline){
            $query_result_friend_offline = "
                SELECT *
                FROM {usr}
                WHERE id in ($str_eselma_offline)
            ";
            $result_friend_offline = get_records_sql_array($query_result_friend_offline);
        }
        
        $smarty->assign('eselma_get_online', $result_friend_online);
        $smarty->assign('eselma_get_offline', $result_friend_offline);
        $smarty->assign('lastminutes', floor(get_config('accessidletimeout') / 60));
        $smarty->assign('eselma_count_online', $result_friend_online_id['count'] );
        $smarty->assign('eselma_count_offline', $result_friend_offline_id['count']);
        return $smarty->fetch('blocktype:eselmaonoff:content.tpl');
    }

    public static function artefactchooser_element($default=null) {
    }

}
