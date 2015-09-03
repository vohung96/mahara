<?php


defined('INTERNAL') || die();



class LeapImportMilestones extends LeapImportArtefactPlugin {

    const STRATEGY_IMPORT_AS_MILESTONE = 1;

    private static $ancestors = array();
    private static $parents = array();

    public static function get_import_strategies_for_entry(SimpleXMLElement $entry, PluginImportLeap $importer) {
        $strategies = array();

        // Mahara can't handle html milestones yet, so don't claim to be able to import them.
        if (PluginImportLeap::is_rdf_type($entry, $importer, 'milestone')
            && (empty($entry->content['type']) || (string)$entry->content['type'] == 'text')) {
            $strategies[] = array(
                'strategy' => self::STRATEGY_IMPORT_AS_MILESTON,
                'score'    => 90,
                'other_required_entries' => array(),
            );
        }

        return $strategies;
    }

    public static function add_import_entry_request_using_strategy(SimpleXMLElement $entry, PluginImportLeap $importer, $strategy, array $otherentries) {
        if ($strategy != self::STRATEGY_IMPORT_AS_MILESTON) {
            throw new ImportException($importer, 'TODO: get_string: unknown strategy chosen for importing entry');
        }
        self::add_import_entry_request_milestone($entry, $importer);
    }


    public static function import_from_requests(PluginImportLeap $importer) {
        $importid = $importer->get('importertransport')->get('importid');
        if ($entry_requests = get_records_select_array('import_entry_requests', 'importid = ? AND plugin = ? AND entrytype = ?', array($importid, 'milestones', 'milestone'))) {
            foreach ($entry_requests as $entry_request) {
                if ($milestoneid = self::create_artefact_from_request($importer, $entry_request)) {
                    if ($milestonefact_requests = get_records_select_array('import_entry_requests', 'importid = ? AND entryparent = ? AND entrytype = ?', array($importid, $entry_request->entryid, 'fact'))) {
                        foreach ($milestonefact_requests as $milestonefact_request) {
                            self::create_artefact_from_request($importer, $milestonefact_request, $milestoneid);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param SimpleXMLElement $entry
     * @param PluginImportLeap $importer
     * @param unknown_type $strategy
     * @param array $otherentries
     * @throws ImportException
     */
    public static function import_using_strategy(SimpleXMLElement $entry, PluginImportLeap $importer, $strategy, array $otherentries) {

        if ($strategy != self::STRATEGY_IMPORT_AS_MILESTONE) {
            throw new ImportException($importer, 'TODO: get_string: unknown strategy chosen for importing entry');
        }

        $artefactmapping = array();
        $artefactmapping[(string)$entry->id] = self::create_milestone($entry, $importer);
        return $artefactmapping;
    }

    /**
     * Get the id of the milestone entry which ultimately contains this entry
     */
    public static function get_ancestor_entryid(SimpleXMLElement $entry, PluginImportLeap $importer) {
        $entryid = (string)$entry->id;

        if (!isset(self::$ancestors[$entryid])) {
            self::$ancestors[$entryid] = null;
            $child = $entry;

            while ($child) {
                $childid = (string)$child->id;

                if (!isset(self::$parents[$childid])) {
                    self::$parents[$childid] = null;

                    foreach ($child->link as $link) {
                        $href = (string)$link['href'];
                        if ($href != $entryid
                            && $importer->curie_equals($link['rel'], PluginImportLeap::NS_LEAP, 'is_part_of')
                            && $importer->entry_has_strategy($href, self::STRATEGY_IMPORT_AS_MILESTONE, 'milestones')) {
                            self::$parents[$childid] = $href;
                            break;
                        }
                    }
                }

                if (!self::$parents[$childid]) {
                    break;
                }
                if ($child = $importer->get_entry_by_id(self::$parents[$childid])) {
                    self::$ancestors[$entryid] = self::$parents[$childid];
                }
            }
        }

        return self::$ancestors[$entryid];
    }


    private static function add_import_entry_request_milestone(SimpleXMLElement $entry, PluginImportLeap $importer) {

     

        if ($ancestorid = self::get_ancestor_entryid($entry, $importer)) {
            $type = 'fact';
        }
        else {
            $type = 'milestone';
        }

        if (isset($entry->author->name) && strlen($entry->author->name)) {
            $authorname = $entry->author->name;
        }
        else {
            $author = $importer->get('usr');
        }

        // Set completiondate and completed status if we can find them
        if ($type === 'fact') {

            $namespaces = $importer->get_namespaces();
            $ns = $importer->get_leap2a_namespace();

            $dates = PluginImportLeap::get_leap_dates($entry, $namespaces, $ns);
            if (!empty($dates['target']['value'])) {
                $completiondate = strtotime($dates['target']['value']);
            }
            $completiondate = empty($completiondate) ? $updated : $completiondate;

            $completed = 0;
            if ($entry->xpath($namespaces[$ns] . ':status[@' . $namespaces[$ns] . ':stage="completed"]')) {
                $completed = 1;
            }
        }

        PluginImportLeap::add_import_entry_request($importer->get('importertransport')->get('importid'), (string)$entry->id, self::STRATEGY_IMPORT_AS_MILESTONE, 'milestones', array(
            'owner'   => $importer->get('usr'),
            'type'    => $type,
            'parent'  => $ancestorid,
            'content' => array(
                'title'       => (string)$entry->title,
                'description' => PluginImportLeap::get_entry_content($entry, $importer),
                'authorname'  => isset($authorname) ? $authorname : null,
                'author'      => isset($author) ? $author : null,
                'ctime'       => (string)$entry->published,
                'mtime'       => (string)$entry->updated,
                'completiondate' => ($type === 'fact') ? $completiondate : null,
                'completed'   => ($type === 'fact') ? $completed : null,
                'tags'        => PluginImportLeap::get_entry_tags($entry),
            ),
        ));
    }


    private static function create_milestone(SimpleXMLElement $entry, PluginImportLeap $importer) {

        // First decide if it's going to be a milestone or a fact depending
        // on whether it has any ancestral milestones.

        if (self::get_ancestor_entryid($entry, $importer)) {
            $artefact = new ArtefactTypeFact();
        }
        else {
            $artefact = new ArtefactTypeMilestone();
        }

        $artefact->set('title', (string)$entry->title);
        $artefact->set('description', PluginImportLeap::get_entry_content($entry, $importer));
        $artefact->set('owner', $importer->get('usr'));
        if (isset($entry->author->name) && strlen($entry->author->name)) {
            $artefact->set('authorname', $entry->author->name);
        }
        else {
            $artefact->set('author', $importer->get('usr'));
        }
        if ($published = strtotime((string)$entry->published)) {
            $artefact->set('ctime', $published);
        }
        if ($updated = strtotime((string)$entry->updated)) {
            $artefact->set('mtime', $updated);
        }

        $artefact->set('tags', PluginImportLeap::get_entry_tags($entry));

        // Set completiondate and completed status if we can find them
        if ($artefact instanceof ArtefactTypeFact) {

            $namespaces = $importer->get_namespaces();
            $ns = $importer->get_leap2a_namespace();

            $dates = PluginImportLeap::get_leap_dates($entry, $namespaces, $ns);
            if (!empty($dates['target']['value'])) {
                $completiondate = strtotime($dates['target']['value']);
            }
            $artefact->set('completiondate', empty($completiondate) ? $artefact->get('mtime') : $completiondate);

            if ($entry->xpath($namespaces[$ns] . ':status[@' . $namespaces[$ns] . ':stage="completed"]')) {
                $artefact->set('completed', 1);
            }
        }

        $artefact->commit();

        return array($artefact->get('id'));
    }

    /**
     * Set fact parents
     */
    public static function setup_relationships(SimpleXMLElement $entry, PluginImportLeap $importer) {
        if ($ancestorid = self::get_ancestor_entryid($entry, $importer)) {
            $ancestorids = $importer->get_artefactids_imported_by_entryid($ancestorid);
            $artefactids = $importer->get_artefactids_imported_by_entryid((string)$entry->id);
            if (empty($artefactids[0])) {
                throw new ImportException($importer, 'Fact artefact not found: ' . (string)$entry->id);
            }
            if (empty($ancestorids[0])) {
                throw new ImportException($importer, 'Milestone artefact not found: ' . $ancestorid);
            }
            $artefact = new ArtefactTypeFact($artefactids[0]);
            $artefact->set('parent', $ancestorids[0]);
            $artefact->commit();
        }
    }

    /**
     * Render import entry requests for Mahara milestones and their facts
     * @param PluginImportLeap $importer
     * @return HTML code for displaying milestones and choosing how to import them
     */
    public static function render_import_entry_requests(PluginImportLeap $importer) {
        $importid = $importer->get('importertransport')->get('importid');
        // Get import entry requests for Mahara milestones
        $entrymilestones = array();
        if ($iermilestones = get_records_select_array('import_entry_requests', 'importid = ? AND entrytype = ?', array($importid, 'milestone'))) {
            foreach ($iermilestones as $iermilestone) {
                $milestone = unserialize($iermilestone->entrycontent);
                $milestone['id'] = $iermilestone->id;
                $milestone['decision'] = $iermilestone->decision;
                if (is_string($iermilestone->duplicateditemids)) {
                    $iermilestone->duplicateditemids = unserialize($iermilestone->duplicateditemids);
                }
                if (is_string($iermilestone->existingitemids)) {
                    $iermilestone->existingitemids = unserialize($iermilestone->existingitemids);
                }
                $milestone['disabled'][PluginImport::DECISION_IGNORE] = false;
                $milestone['disabled'][PluginImport::DECISION_ADDNEW] = false;
                $milestone['disabled'][PluginImport::DECISION_APPEND] = true;
                $milestone['disabled'][PluginImport::DECISION_REPLACE] = true;
                if (!empty($iermilestone->duplicateditemids)) {
                    $duplicated_item = artefact_instance_from_id($iermilestone->duplicateditemids[0]);
                    $milestone['duplicateditem']['id'] = $duplicated_item->get('id');
                    $milestone['duplicateditem']['title'] = $duplicated_item->get('title');
                    $res = $duplicated_item->render_self(array());
                    $milestone['duplicateditem']['html'] = $res['html'];
                }
                else if (!empty($iermilestone->existingitemids)) {
                    foreach ($iermilestone->existingitemids as $id) {
                        $existing_item = artefact_instance_from_id($id);
                        $res = $existing_item->render_self(array());
                        $milestone['existingitems'][] = array(
                            'id'    => $existing_item->get('id'),
                            'title' => $existing_item->get('title'),
                            'html'  => $res['html'],
                        );
                    }
                }
                // Get import entry requests of facts in the milestone
                $entryfacts = array();
                if ($ierfacts = get_records_select_array('import_entry_requests', 'importid = ? AND entrytype = ? AND entryparent = ?',
                        array($importid, 'fact', $iermilestone->entryid))) {
                    foreach ($ierfacts as $ierfact) {
                        $fact = unserialize($ierfact->entrycontent);
                        $fact['id'] = $ierfact->id;
                        $fact['decision'] = $ierfact->decision;
                        $fact['completiondate'] = format_date($fact['completiondate'], 'strftimedate');
                        $fact['disabled'][PluginImport::DECISION_IGNORE] = false;
                        $fact['disabled'][PluginImport::DECISION_ADDNEW] = false;
                        $fact['disabled'][PluginImport::DECISION_APPEND] = true;
                        $fact['disabled'][PluginImport::DECISION_REPLACE] = true;
                        $entryfacts[] = $fact;
                    }
                }
                $milestone['entryfacts'] = $entryfacts;
                $entrymilestones[] = $milestone;
            }
        }
        $smarty = smarty_core();
        $smarty->assign_by_ref('displaydecisions', $importer->get('displaydecisions'));
        $smarty->assign_by_ref('entrymilestones', $entrymilestones);
        return $smarty->fetch('artefact:milestones:import/milestones.tpl');
    }
}
