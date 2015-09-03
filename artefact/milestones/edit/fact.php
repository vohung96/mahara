<?php


define('INTERNAL', true);
define('MENUITEM', 'content/milestones');

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/init.php');
require_once('pieforms/pieform.php');
require_once('pieforms/pieform/elements/calendar.php');
require_once(get_config('docroot') . 'artefact/lib.php');
safe_require('artefact','milestones');

if (!PluginArtefactMilestones::is_active()) {
    throw new AccessDeniedException(get_string('plugindisableduser', 'mahara', get_string('milestones','artefact.milestones')));
}

define('TITLE', get_string('editfact','artefact.milestones'));

$id = param_integer('id');
$fact = new ArtefactTypeFact($id);
if (!$USER->can_edit_artefact($fact)) {
    throw new AccessDeniedException(get_string('accessdenied', 'error'));
}

$form = ArtefactTypeFact::get_form($fact->get('parent'), $fact);

$smarty = smarty();
$smarty->assign('editform', $form);
$smarty->assign('PAGEHEADING', hsc(get_string("editingfact", "artefact.milestones")));
$smarty->display('artefact:milestones:edit.tpl');
