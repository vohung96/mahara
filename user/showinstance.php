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
define('SECTION_PLUGINTYPE', 'artefact');
define('SECTION_PLUGINNAME', 'internal');
require(dirname(dirname(__FILE__)) . '/init.php');
require_once(get_config('docroot') . 'lib/dml.php');

$instance_id = param_integer('instance_id');
$enable = param_boolean('enable');

$update_string = 'a:1:{s:12:"showinstance";b:'. (int) $enable .';}';
$update_instace = get_record_select('block_instance', 'id = ?', array($instance_id));
$update_instace->configdata = $update_string;
update_record('block_instance', $update_instace);
redirect('/user/view.php');



