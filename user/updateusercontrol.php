<?php
/**
 *
 * @package    mahara
 * @subpackage core
 * @author     Stacey Walker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

define('INTERNAL', 1);
require(dirname(dirname(__FILE__)) . '/init.php');
global $USER;

$user_control_value = param_variable('user_control_value', '');
$USER->set_account_preference('friendscontrol', $user_control_value);
$update_settings = true;

$smarty = smarty();
$smarty->assign('update_settings', $update_settings);
$smarty->display('user/usercontrolvalue.tpl');