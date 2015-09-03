<?php

define('INTERNAL', true);
define('MENUITEM', 'content/milestones');

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/init.php');
require_once('pieforms/pieform.php');
safe_require('artefact','milestones');

define('TITLE', get_string('deletemilestone','artefact.milestones'));

$id = param_integer('id');
$todelete = new ArtefactTypeMilestone($id);
if (!$USER->can_edit_artefact($todelete)) {
    throw new AccessDeniedException(get_string('accessdenied', 'error'));
}

$deleteform = array(
    'name' => 'deletemilestoneform',
    'plugintype' => 'artefact',
    'pluginname' => 'milestone',
    'renderer' => 'div',
    'elements' => array(
        'submit' => array(
            'type' => 'submitcancel',
            'value' => array(get_string('deletemilestone','artefact.milestones'), get_string('cancel')),
            'goto' => get_config('wwwroot') . '/artefact/milestones/index.php',
        ),
    )
);
$form = pieform($deleteform);

$smarty = smarty();
$smarty->assign('form', $form);
$smarty->assign('PAGEHEADING', $todelete->get('title'));
$smarty->assign('subheading', get_string('deletethismilestone','artefact.milestones',$todelete->get('title')));
$smarty->assign('message', get_string('deletemilestoneconfirm','artefact.milestones'));
$smarty->display('artefact:milestones:delete.tpl');

// calls this function first so that we can get the artefact and call delete on it
function deletemilestoneform_submit(Pieform $form, $values) {
    global $SESSION, $todelete;

    $todelete->delete();
    $SESSION->add_ok_msg(get_string('milestonedeletedsuccessfully', 'artefact.milestones'));

    redirect('/artefact/milestones/index.php');
}
