<?php


define('INTERNAL', 1);
define('JSON', 1);

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'milestones');
require_once(get_config('docroot') . 'blocktype/lib.php');
require_once(get_config('docroot') . 'artefact/milestones/blocktype/milestones/lib.php');

$offset = param_integer('offset', 0);
$limit = param_integer('limit', 10);

if ($blockid = param_integer('block', null)) {
    $bi = new BlockInstance($blockid);
    if (!can_view_view($bi->get('view'))) {
        json_reply(true, get_string('accessdenied', 'error'));
    }
    $options = $configdata = $bi->get('configdata');

    $facts = ArtefactTypeFact::get_facts($configdata['artefactid'], $offset, $limit);

    $template = 'artefact:milestones:factrows.tpl';
    $baseurl = $bi->get_view()->get_url();
    $baseurl .= ((false === strpos($baseurl, '?')) ? '?' : '&') . 'block=' . $blockid;
    $pagination = array(
        'baseurl'   => $baseurl,
        'id'        => 'block' . $blockid . '_pagination',
        'datatable' => 'facttable_' . $blockid,
        'jsonscript' => 'artefact/milestones/viewfacts.json.php',
    );
}
else {
    $milestoneid = param_integer('artefact');
    $viewid = param_integer('view');
    if (!can_view_view($viewid)) {
        json_reply(true, get_string('accessdenied', 'error'));
    }
    $options = array('viewid' => $viewid);
    $facts = ArtefactTypeFact::get_facts($milestoneid, $offset, $limit);

    $template = 'artefact:milestones:factrows.tpl';
    $baseurl = get_config('wwwroot') . 'artefact/artefact.php?artefact=' . $milestoneid . '&view=' . $options['viewid'];
    $pagination = array(
        'baseurl' => $baseurl,
        'id' => 'fact_pagination',
        'datatable' => 'facttable',
        'jsonscript' => 'artefact/milestones/viewfacts.json.php',
    );

}
ArtefactTypeFact::render_facts($facts, $template, $options, $pagination);

json_reply(false, (object) array('message' => false, 'data' => $facts));
