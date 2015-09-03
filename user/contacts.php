<?php

/**
 *
 * @package    mahara
 * @subpackage core
 * @author     Catalyst IT Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL version 3 or later
 * @copyright  For copyright information on Mahara, please see the README file distributed with this software.
 *
 */
define('INTERNAL', 1);
define('PUBLIC', 1);
define('MENUITEM', 'mycontacts/mycontacts');

// Technically these are lies, but we set them like this to hook in the right
// plugin stylesheet. This file should be provided by artefact/internal anyway.
define('SECTION_PLUGINTYPE', 'core');
define('SECTION_PLUGINNAME', 'user');
define('SECTION_PAGE', 'contacts');
define('TITLE', 'Contacts');

require(dirname(dirname(__FILE__)) . '/init.php');
require_once('pieforms/pieform.php');
require_once(get_config('libroot') . 'view.php');
require_once(get_config('libroot') . 'user.php');
require_once('searchlib.php');
global $USER;

// For logged-out users we show "access denied" in order to prevent an enumeration attack
if (!$USER->is_logged_in()) {
    throw new AccessDeniedException(get_string('youcannotviewthisuserscontacts', 'error'));
}

$view = $USER->get_contacts_view();
$viewcontent = $view->build_rows();
$javascript = array('paginator', 'lib/pieforms/static/core/pieforms.js', 'expandable', 'groupbox', 'chat');

// Set up theme
$viewtheme = $view->get('theme');
if ($viewtheme && $THEME->basename != $viewtheme) {
    $THEME = new Theme($viewtheme);
}
$stylesheets = array('<link rel="stylesheet" type="text/css" href="' . append_version_number(get_config('wwwroot') . 'theme/views.css') . '">');
$stylesheets = array_merge($stylesheets, $view->get_all_blocktype_css());
// include slimbox2 js and css files, if it is enabled...
if (get_config_plugin('blocktype', 'gallery', 'useslimbox2')) {
    $langdir = (get_string('thisdirection', 'langconfig') == 'rtl' ? '-rtl' : '');
    $stylesheets = array_merge($stylesheets, array('<script type="application/javascript" src="' . append_version_number(get_config('wwwroot') . 'lib/slimbox2/js/slimbox2.js') . '"></script>',
        '<link rel="stylesheet" type="text/css" href="' . append_version_number(get_config('wwwroot') . 'lib/slimbox2/css/slimbox2' . $langdir . '.css') . '">'
    ));
}
$filter_friend = param_alpha('filter', 'all');
$offset_friend = param_integer('offset', 0);
$limit_friend = 1000;

$friend_array = search_friend($filter_friend, $limit_friend, $offset_friend);
$friend_id_list = $friend_array["data"];
$list_friend = [];
foreach ($friend_id_list as $friend) {
    $get_friend = get_user($friend["id"]);
    $list_friend[] = $get_friend;
}

// Set up skin, if the page has one
$viewskin = $view->get('skin');
$owner = $view->get('owner');
$issiteview = $view->get('institution') == 'mahara';
if ($viewskin && get_config('skins') && can_use_skins($owner, false, $issiteview) && (!isset($THEME->skins) || $THEME->skins !== false)) {
    $skin = array('skinid' => $viewskin, 'viewid' => $view->get('id'));
    $skindata = unserialize(get_field('skin', 'viewskin', 'id', $viewskin));
} else {
    $skin = false;
}

$smarty = smarty(
        $javascript, $stylesheets, array(), array(
    'stylesheets' => array('style/views.css', 'style/chat.css', 'style/screen.css'),
    'sidebars' => false,
    'skin' => $skin
        )
);

$smarty->assign('viewcontent', $viewcontent);
$smarty->assign('list_friend', $list_friend);
$smarty->assign('USERID', $userid);
$smarty->assign('PAGEHEADING', hsc(get_string('contacts')));
$smarty->display('user/contacts.tpl');
