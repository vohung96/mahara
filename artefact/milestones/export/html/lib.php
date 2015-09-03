<?php


defined('INTERNAL') || die();

class HtmlExportMilestones extends HtmlExportArtefactPlugin {

    public function pagination_data($artefact) {
        if ($artefact instanceof ArtefactTypeMilestone) {
            return array(
                'perpage'    => 10,
                'childcount' => $artefact->count_children(),
                'plural'     => get_string('milestones', 'artefact.milestones'),
            );
        }
    }

    public function dump_export_data() {
        foreach ($this->exporter->get('artefacts') as $artefact) {
            if ($artefact instanceof ArtefactTypeMilestone) {
                $this->paginate($artefact);
            }
        }
    }

    public function get_summary() {
        $smarty = $this->exporter->get_smarty();
        $milestones = array();
        foreach ($this->exporter->get('artefacts') as $artefact) {
            if ($artefact instanceof ArtefactTypeMilestone) {
                $milestones[] = array(
                    'link' => 'files/milestones/' . PluginExportHtml::text_to_URLpath(PluginExportHtml::text_to_filename($artefact->get('title'))) . '/index.html',
                    'title' => $artefact->get('title'),
                );
            }
        }
        $smarty->assign('milestones', $milestones);

        return array(
            'title' => get_string('milestones', 'artefact.milestones'),
            'description' => $smarty->fetch('export:html/milestones:summary.tpl'),
        );
    }

    public function get_summary_weight() {
        return 40;
    }
}
