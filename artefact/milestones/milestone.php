<?php


define('INTERNAL', 1);
define('MENUITEM', 'content/milestones');
define('SECTION_PLUGINTYPE', 'artefact');
define('SECTION_PLUGINNAME', 'milestones');
define('SECTION_PAGE', 'milestones');

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'milestones');
if (!PluginArtefactMilestones::is_active()) {
    throw new AccessDeniedException(get_string('plugindisableduser', 'mahara', get_string('milestones','artefact.milestones')));
}

define('TITLE', get_string('Facts','artefact.milestones'));

$id = param_integer('id');

// offset and limit for pagination
$offset = param_integer('offset', 0);
$limit  = param_integer('limit', 10);

$milestone = new ArtefactTypeMilestone($id);
if (!$USER->can_edit_artefact($milestone)) {
    throw new AccessDeniedException(get_string('accessdenied', 'error'));
}


$facts = ArtefactTypeFact::get_facts($milestone->get('id'), $offset, $limit);
ArtefactTypeFact::build_facts_list_html($facts);

$js = <<< EOF
addLoadFact(function () {
    {$facts['pagination_js']}
});
EOF;

$smarty = smarty(array('paginator'));
$smarty->assign_by_ref('facts', $facts);
$smarty->assign_by_ref('milestone', $id);
$smarty->assign_by_ref('tags', $milestone->get('tags'));
$smarty->assign_by_ref('owner', $milestone->get('owner'));
$smarty->assign('strnofactsaddone',
    get_string('nofactsaddone', 'artefact.milestones',
    '<a href="' . get_config('wwwroot') . 'artefact/milestones/new.php?id='.$milestone->get('id').'">', '</a>'));
$smarty->assign('milestonesfactsdescription', get_string('milestonesfactsdescription', 'artefact.milestones', get_string('newfact', 'artefact.milestones')));
$smarty->assign('PAGEHEADING', get_string("milestonesfacts", "artefact.milestones",$milestone->get('title')));
$smarty->assign('INLINEJAVASCRIPT', $js);
$smarty->display('artefact:milestones:milestone.tpl');
