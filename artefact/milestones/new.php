<?php


define('INTERNAL', 1);
define('MENUITEM', 'content/milestones');
define('SECTION_PLUGINTYPE', 'artefact');
define('SECTION_PLUGINNAME', 'milestones');

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'milestones');
if (!PluginArtefactMilestones::is_active()) {
    throw new AccessDeniedException(get_string('plugindisableduser', 'mahara', get_string('milestones','artefact.milestones')));
}

$id = param_integer('id',0);
if ($id) {
    $milestone = new ArtefactTypeMilestone($id);
    if (!$USER->can_edit_artefact($milestone)) {
        throw new AccessDeniedException(get_string('accessdenied', 'error'));
    }
    define('TITLE', get_string('newfact','artefact.milestones'));
    $form = ArtefactTypeFact::get_form($id);
}
else {
    define('TITLE', get_string('newmilestone','artefact.milestones'));
    $form = ArtefactTypeMilestone::get_form();
}

$smarty =& smarty();
$smarty->assign_by_ref('form', $form);
$smarty->assign_by_ref('PAGEHEADING', hsc(TITLE));
$smarty->display('artefact:milestones:new.tpl');
