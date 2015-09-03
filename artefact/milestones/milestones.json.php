<?php


define('INTERNAL', 1);
define('JSON', 1);

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'milestones');

$limit = param_integer('limit', 10);
$offset = param_integer('offset', 0);

$milestones = ArtefactTypeMilestone::get_milestones($offset, $limit);
ArtefactTypeMilestone::build_milestones_list_html($milestones);

json_reply(false, (object) array('message' => false, 'data' => $milestones));
