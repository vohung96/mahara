<?php


defined('INTERNAL') || die();

function xmldb_artefact_milestones_upgrade($oldversion=0) {

    if ($oldversion < 2010072302) {
        set_field('artefact', 'container', 1, 'artefacttype', 'milestone');
    }

    return true;
}
