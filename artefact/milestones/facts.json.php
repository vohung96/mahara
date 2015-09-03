<?php


define('INTERNAL', 1);
define('JSON', 1);

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'milestones');

$milestone = param_integer('id');
$limit = param_integer('limit', 3);
$offset = param_integer('offset', 0);

if (!$USER->can_edit_artefact(new ArtefactTypeMilestone($milestone))) {
    json_reply(true, get_string('accessdenied', 'error'));
}

$facts = ArtefactTypeFact::get_facts($milestone, $offset, $limit);
ArtefactTypeFact::build_facts_list_html($facts);

json_reply(false, (object) array('message' => false, 'data' => $facts));
