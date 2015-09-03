<?php
/**
 *
 * @package    mahara
 * @subpackage artefact-plans
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */

define('INTERNAL', 1);
define('JSON', 1);

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'plans');
require_once(get_config('docroot') . 'blocktype/lib.php');
require_once(get_config('docroot') . 'artefact/plans/blocktype/plans/lib.php');

$offset = param_integer('offset', 0);
$limit = param_integer('limit', 3);

if ($blockid = param_integer('block', null)) {
    $bi = new BlockInstance($blockid);

    $tasks = ArtefactTypeTask::get_alltasks($blockid, $offset, $limit);

    $template = 'artefact:plans:alltaskrows.tpl';
    $baseurl = $bi->get_view()->get_url();
    $baseurl .= ((false === strpos($baseurl, '?')) ? '?' : '&') . 'block=' . $blockid;
    $pagination = array(
        'baseurl'   => $baseurl,
        'id'        => 'block' . $blockid . '_pagination',
        'datatable' => 'tasktable_' . $blockid,
        'jsonscript' => 'artefact/plans/viewtasks.json.php',
    );
}

ArtefactTypeTask::render_tasks($tasks, $template, $options, $pagination);

json_reply(false, (object) array('message' => false, 'data' => $tasks));
