<?php

define('INTERNAL', 1);
define('JSON', 1);

require(dirname(dirname(dirname(__FILE__))) . '/init.php');
safe_require('artefact', 'milestones');
require_once(get_config('docroot') . 'blocktype/lib.php');
require_once(get_config('docroot') . 'artefact/milestones/blocktype/milestones/lib.php');

$offset = param_integer('offset', 0);
$limit = param_integer('limit', 3);

if ($blockid = param_integer('block', null)) {
    $bi = new BlockInstance($blockid);

    $facts = ArtefactTypeFact::get_allfacts($blockid, $offset, $limit);

    $template = 'artefact:milestones:allfactrows.tpl';
    $baseurl = $bi->get_view()->get_url();
    $baseurl .= ((false === strpos($baseurl, '?')) ? '?' : '&') . 'block=' . $blockid;
    $pagination = array(
        'baseurl'   => $baseurl,
        'id'        => 'block' . $blockid . '_pagination',
        'datatable' => 'facttable_' . $blockid,
        'jsonscript' => 'artefact/milestones/viewfacts.json.php',
    );
}

ArtefactTypeFact::render_facts($facts, $template, $options, $pagination);

json_reply(false, (object) array('message' => false, 'data' => $facts));
