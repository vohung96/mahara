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
require(dirname(dirname(__FILE__)) . '/init.php');
require_once('group.php');
require_once('searchlib.php');

$filter = param_alpha('filter', 'canjoin');
$offset = param_integer('offset', 0);
$groupsperpage = 100;
$query = param_variable('query', '');

// check that the filter is valid, if not default to 'all'
if (in_array($filter, array('member', 'notmember', 'canjoin'))) {
    $type = $filter;
}
else { // all or some other text
    $filter = 'all';
    $type = 'all';
}

$groups = search_group($query, $groupsperpage, $offset, $type);

// gets more data about the groups found by search_group
// including type if the user is associated with the group in some way
if ($groups['data']) {
    $groupids = array();
    foreach ($groups['data'] as $group) {
        $groupids[] = $group->id;
    }
    $groups['data'] =  get_records_sql_array("
        SELECT g1.id, g1.name, g1.description, g1.public, g1.jointype, g1.request, g1.grouptype, g1.submittableto,
            g1.hidemembers, g1.hidemembersfrommembers, g1.urlid, g1.role, g1.membershiptype, g1.membercount, COUNT(gmr.member) AS requests,
            g1.editwindowstart, g1.editwindowend
        FROM (
            SELECT g.id, g.name, g.description, g.public, g.jointype, g.request, g.grouptype, g.submittableto,
                g.hidemembers, g.hidemembersfrommembers, g.urlid, t.role, t.membershiptype, COUNT(gm.member) AS membercount,
                g.editwindowstart, g.editwindowend
            FROM {group} g
            LEFT JOIN {group_member} gm ON (gm.group = g.id)
            LEFT JOIN (
                SELECT g.id, 'admin' AS membershiptype, gm.role AS role
                FROM {group} g
                INNER JOIN {group_member} gm ON (gm.group = g.id AND gm.member = ? AND gm.role = 'admin')
                UNION
                SELECT g.id, 'member' AS membershiptype, gm.role AS role
                FROM {group} g
                INNER JOIN {group_member} gm ON (g.id = gm.group AND gm.member = ? AND gm.role != 'admin')
                UNION
                SELECT g.id, 'invite' AS membershiptype, gmi.role
                FROM {group} g
                INNER JOIN {group_member_invite} gmi ON (gmi.group = g.id AND gmi.member = ?)
                UNION
                SELECT g.id, 'request' AS membershiptype, NULL as role
                FROM {group} g
                INNER JOIN {group_member_request} gmr ON (gmr.group = g.id AND gmr.member = ?)
            ) t ON t.id = g.id
            WHERE g.id IN (" . implode($groupids, ',') . ')
            GROUP BY g.id, g.name, g.description, g.public, g.jointype, g.request, g.grouptype, g.submittableto,
                g.hidemembers, g.hidemembersfrommembers, g.urlid, t.role, t.membershiptype, g.editwindowstart, g.editwindowend
        ) g1
        LEFT JOIN {group_member_request} gmr ON (gmr.group = g1.id)
        GROUP BY g1.id, g1.name, g1.description, g1.public, g1.jointype, g1.request, g1.grouptype, g1.submittableto,
            g1.hidemembers, g1.hidemembersfrommembers, g1.urlid, g1.role, g1.membershiptype, g1.membercount, g1.editwindowstart, g1.editwindowend
        ORDER BY g1.name',
        array($USER->get('id'), $USER->get('id'), $USER->get('id'), $USER->get('id'))
    );
}

group_prepare_usergroups_for_display($groups['data'], 'find');
$smarty = smarty();
$smarty->assign('groups', $groups);
$smarty->display('group/searchgroup.tpl');
