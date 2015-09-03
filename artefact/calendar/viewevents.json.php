<?php
/**
 *
 * @package    mahara
 * @subpackage artefact-events
 *
 */

define('INTERNAL', 1);
define('JSON', 1);

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'calendar');
require_once(get_config('docroot') . 'blocktype/lib.php');
require_once(get_config('docroot') . 'artefact/calendar/blocktype/events/lib.php');
require_once(get_config('docroot') . 'artefact/calendar/lib.php');

$offset = param_integer('offset', 0);
$limit = param_integer('limit', 3);

if ($blockid = param_integer('block', null)) {
    $bi = new BlockInstance($blockid);
    $events = ArtefactTypeEvent::get_all_events($blockid, $offset, $limit);

    $template = 'artefact:calendar:eventrows.tpl';
    $baseurl = $bi->get_view()->get_url();
    $baseurl .= ((false === strpos($baseurl, '?')) ? '?' : '&') . 'block=' . $blockid;
    $pagination = array(
        'baseurl'   => $baseurl,
        'id'        => 'block' . $blockid . '_pagination',
        'datatable' => 'eventtable_' . $blockid,
        'jsonscript' => 'artefact/calendar/viewevents.json.php',
    );
}
else {
    $blockid = param_integer('id');
    $events = ArtefactTypeEvent::get_all_events($blockid, $offset, $limit);

    $template = 'artefact:calendar:eventrows.tpl';
    $baseurl = get_config('wwwroot') . 'artefact/artefact.php?artefact=' . $blockid;
    $pagination = array(
        'baseurl' => $baseurl,
        'id' => 'event_pagination',
        'datatable' => 'eventtable',
        'jsonscript' => 'artefact/calendar/viewevents.json.php',
    );

}
ArtefactTypeEvent::render_events($events, $template, $pagination);

json_reply(false, (object) array('message' => false, 'data' => $events));
