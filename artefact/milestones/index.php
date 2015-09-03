<?php



define('INTERNAL', 1);
//define('MENUITEM', 'content/milestones');
define('SECTION_PLUGINTYPE', 'artefact');
define('SECTION_PLUGINNAME', 'milestones');
define('SECTION_PAGE', 'index');

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'milestones');

define('TITLE', get_string('milestones','artefact.milestones'));

if (!PluginArtefactMilestones::is_active()) {
    throw new AccessDeniedException(get_string('plugindisableduser', 'mahara', get_string('milestones','artefact.milestones')));
}

// offset and limit for pagination
$offset = param_integer('offset', 0);
$limit  = param_integer('limit', 10);

$milestones = ArtefactTypeMilestone::get_milestones($offset, $limit);
ArtefactTypeMilestone::build_milestones_list_html($milestones);

$js = <<< EOF
addLoadFact(function () {
    {$milestones['pagination_js']}
});
EOF;

$smarty = smarty(array('paginator'));
$smarty->assign_by_ref('milestones', $milestones);
$smarty->assign('strnomilestonesaddone',
    get_string('nomilestonesaddone', 'artefact.milestones',
    '<a href="' . get_config('wwwroot') . 'artefact/milestones/new.php">', '</a>'));
$smarty->assign('PAGEHEADING', hsc(get_string("milestones", "artefact.milestones")));
$smarty->assign('INLINEJAVASCRIPT', $js);
$smarty->display('artefact:milestones:index.tpl');
