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
require_once('pieforms/pieform.php');
require_once('searchlib.php');
require_once(get_config('docroot') . 'artefact/lib.php');

class PluginBlocktypeEselmaprofileinfo extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.internal/eselmaprofileinfo');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.internal/eselmaprofileinfo');
    }

    public static function get_categories() {
        return array('internal' => 26000);
    }

    public static function get_instance_config_javascript(BlockInstance $instance) {
        return array('js/configform.js');
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        global $USER;
        $configdata = $instance->get('configdata');
        $instance_id = $instance->get('id');
        $showinstance = isset($configdata["showinstance"]) ? (bool) $configdata["showinstance"]: True;

        $loggedinid = $USER->get('id');
        $viewowner = get_field('view', 'owner', 'id', $instance->get('view'));
        if ($loggedinid && $loggedinid == $viewowner) {
            $ownprofile = true;
        } else {
            $ownprofile = false;
        }

        //Get information of user
        $query_user_info = "
                SELECT *
                FROM {artefact}
                WHERE owner = ? AND artefacttype in ('address', 'mobilenumber', 'email')";
        $result_user_info = get_records_sql_array($query_user_info, array($viewowner));

        foreach ($result_user_info as $result) {
            if ($result->artefacttype == "email") {
                $eselma_email = $result->title;
            } else if ($result->artefacttype == "mobilenumber"){
                $eselma_mobilenumber = $result->title;
            } else if ($result->artefacttype == "address"){
                $eselma_address = $result->title;
            }
        }

        //Get user's social media
        $query_social_media = "
            SELECT title, description, note FROM {artefact}
            WHERE owner = ? AND artefacttype = 'socialprofile'
            ORDER BY description ASC";

        $data = get_records_sql_array($query_social_media, array($viewowner));

        $smarty = smarty_core();
        $smarty->assign('showinstance', $showinstance);
        $smarty->assign('instance_id', $instance_id);
        $smarty->assign('socialprofiles', $data);
        $smarty->assign('ownprofile', $ownprofile);
        $smarty->assign('eselma_address', $eselma_address);
        $smarty->assign('eselma_mobilenumber', $eselma_mobilenumber);
        $smarty->assign('eselma_email', $eselma_email);

        return $smarty->fetch('blocktype:eselmaprofileinfo:content.tpl');
    }

    public static function artefactchooser_element($default=null) {
    }
}
