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
require_once(get_config('docroot') . 'artefact/lib.php');

class PluginBlocktypeEselmaaboutme extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.internal/eselmaaboutme');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.internal/eselmaaboutme');
    }

    public static function get_categories() {
        return array('internal' => 26000);
    }

    public static function get_instance_config_javascript(BlockInstance $instance) {
        return array('js/configform.js');
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

        $query_user = "
                SELECT * 
                FROM {artefact}
                WHERE owner = ? AND artefacttype in ('firstname', 'lastname', 'studentid', 'preferredname', 'introduction')";
        $result_user = get_records_sql_array($query_user, array($viewowner));

        foreach ($result_user as $result){
            if ($result->artefacttype == 'firstname'){
                $eselma_firstname = $result->title;
            } else if ($result->artefacttype == 'lastname') {
                $eselma_lastname = $result->title;
            } else if ($result->artefacttype == 'studentid') {
                $eselma_studentid = $result->title;
            } else if ($result->artefacttype == 'preferredname') {
                $eselma_preferredname = $result->title;
            } else if ($result->artefacttype == 'introduction') {
                $eselma_introduction = $result->title;
            }
        }

        $smarty = smarty_core();
        $smarty->assign('ownprofile', $ownprofile);
        $smarty->assign('eselma_firstname', $eselma_firstname);
        $smarty->assign('eselma_lastname', $eselma_lastname);
        $smarty->assign('eselma_studentid', $eselma_studentid);
        $smarty->assign('eselma_preferredname', $eselma_preferredname);
        $smarty->assign('eselma_introduction', $eselma_introduction);

        return $smarty->fetch('blocktype:eselmaaboutme:content.tpl');
    }

    public static function artefactchooser_element($default=null) {
    }

}
