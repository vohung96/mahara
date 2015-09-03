<?php


defined('INTERNAL') || die();

class LeapExportElementMilestone extends LeapExportElement {

    public function get_leap_type() {
        return 'milestone';
    }

    public function get_template_path() {
        return 'export:leap/milestones:milestone.tpl';
    }
}

class LeapExportElementFact extends LeapExportElementMilestone {

    public function assign_smarty_vars() {
        parent::assign_smarty_vars();
        $this->smarty->assign('completion', $this->artefact->get('completed') ? 'completed' : 'milestonened');
    }

    public function get_dates() {
        return array(
            array(
                'point' => 'target',
                'date'  => format_date($this->artefact->get('completiondate'), 'strftimew3cdate'),
            ),
        );
    }
}
