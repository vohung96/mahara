<?php

/**
 *
 * @package    mahara
 * @subpackage core
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */
define('INTERNAL', 1);
require(dirname(dirname(__FILE__)) . '/init.php');
require_once('searchlib.php');
require_once('group.php');
global $USER;

$query = param_variable('query', '');
$offset = param_integer('offset', 0);
$limit = 10;
$options = array('exclude' => $USER->get('id'));
$data = search_user($query, $limit, $offset, $options);
$user_data = [];
foreach ($data["data"] as $user_info) {
    $user_info["is_friend"] = is_friend($user_info["id"], $USER->get('id'));
    $user_data[] = $user_info;
}
$smarty = smarty();
$smarty->assign('user_data', $user_data);
$smarty->display('user/searchfriend.tpl');
