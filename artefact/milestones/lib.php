<?php


defined('INTERNAL') || die();

class PluginArtefactMilestones extends PluginArtefact {

    public static function get_artefact_types() {
        return array(
            'fact',
            'milestone',
        );
    }

    public static function get_block_types() {
        return array();
    }

    public static function get_plugin_name() {
        return 'milestones';
    }

    public static function is_active() {
        return get_field('artefact_installed', 'active', 'name', 'milestones');
    }

    public static function menu_items() {
        return array(
            'pages/milestones' => array(
                'path' => 'pages/milestones',
                'url'  => 'artefact/milestones/index.php',
                'title' => get_string('Milestones', 'artefact.milestones'),
                'weight' => 60,
            ),
        );
    }

    public static function get_artefact_type_content_types() {
        return array(
            'fact' => array('text'),
        );
    }

    public static function progressbar_link($artefacttype) {
        return 'artefact/milestones/index.php';
    }
}

class ArtefactTypeMilestone extends ArtefactType {

    public function __construct($id = 0, $data = null) {
        parent::__construct($id, $data);
        if (empty($this->id)) {
            $this->container = 1;
        }
    }

    public static function get_links($id) {
        return array(
            '_default' => get_config('wwwroot') . 'artefact/milestones/milestone.php?id=' . $id,
        );
    }

    public function delete() {
        if (empty($this->id)) {
            return;
        }

        db_begin();
        parent::delete();
        db_commit();
    }

    public static function get_icon($options=null) {
        global $THEME;
        return $THEME->get_url('images/milestone.png', false, 'artefact/milestones');
    }

    public static function is_singular() {
        return false;
    }


    /**
     * This function returns a list of the given user's milestones.
     *
     * @param limit how many milestones to display per page
     * @param offset current page to display
     * @return array (count: integer, data: array)
     */
    public static function get_milestones($offset=0, $limit=10) {
        global $USER;

        ($milestones = get_records_sql_array("SELECT * FROM {artefact}
                                        WHERE owner = ? AND artefacttype = 'milestone'
                                        ORDER BY title ASC", array($USER->get('id')), $offset, $limit))
                                        || ($milestones = array());
        foreach ($milestones as &$milestone) {
            if (!isset($milestone->tags)) {
                $milestone->tags = ArtefactType::artefact_get_tags($milestone->id);
            }
            $milestone->description = '<p>' . preg_replace('/\n\n/','</p><p>', $milestone->description) . '</p>';
        }
        $result = array(
            'count'  => count_records('artefact', 'owner', $USER->get('id'), 'artefacttype', 'milestone'),
            'data'   => $milestones,
            'offset' => $offset,
            'limit'  => $limit,
        );

        return $result;
    }

    /**
     * Builds the milestones list table
     *
     * @param milestones (reference)
     */
    public static function build_milestones_list_html(&$milestones) {
        $smarty = smarty_core();
        $smarty->assign_by_ref('milestones', $milestones);
        $milestones['tablerows'] = $smarty->fetch('artefact:milestones:milestoneslist.tpl');
        $pagination = build_pagination(array(
            'id' => 'milestonelist_pagination',
            'class' => 'center',
            'url' => get_config('wwwroot') . 'artefact/milestones/index.php',
            'jsonscript' => 'artefact/milestones/milestones.json.php',
            'datatable' => 'milestoneslist',
            'count' => $milestones['count'],
            'limit' => $milestones['limit'],
            'offset' => $milestones['offset'],
            'firsttext' => '',
            'previoustext' => '',
            'nexttext' => '',
            'lasttext' => '',
            'numbersincludefirstlast' => false,
            'resultcounttextsingular' => get_string('milestone', 'artefact.milestones'),
            'resultcounttextplural' => get_string('milestones', 'artefact.milestones'),
        ));
        $milestones['pagination'] = $pagination['html'];
        $milestones['pagination_js'] = $pagination['javascript'];
    }

    public static function validate(Pieform $form, $values) {
        global $USER;
        if (!empty($values['milestone'])) {
            $id = (int) $values['milestone'];
            $artefact = new ArtefactTypeMilestone($id);
            if (!$USER->can_edit_artefact($artefact)) {
                $form->set_error('submit', get_string('canteditdontownmilestone', 'artefact.milestones'));
            }
        }
    }

    public static function submit(Pieform $form, $values) {
        global $USER, $SESSION;

        $new = false;

        if (!empty($values['milestone'])) {
            $id = (int) $values['milestone'];
            $artefact = new ArtefactTypeMilestone($id);
        }
        else {
            $artefact = new ArtefactTypeMilestone();
            $artefact->set('owner', $USER->get('id'));
            $new = true;
        }

        $artefact->set('title', $values['title']);
        $artefact->set('description', $values['description']);
        if (get_config('licensemetadata')) {
            $artefact->set('license', $values['license']);
            $artefact->set('licensor', $values['licensor']);
            $artefact->set('licensorurl', $values['licensorurl']);
        }
        $artefact->set('tags', $values['tags']);
        $artefact->commit();

        $SESSION->add_ok_msg(get_string('milestonesavedsuccessfully', 'artefact.milestones'));

        if ($new) {
            redirect('/artefact/milestones/milestone.php?id='.$artefact->get('id'));
        }
        else {
            redirect('/artefact/milestones/index.php');
        }
    }

    /**
    * Gets the new/edit milestones pieform
    *
    */
    public static function get_form($milestone=null) {
        require_once(get_config('libroot') . 'pieforms/pieform.php');
        require_once('license.php');
        $elements = call_static_method(generate_artefact_class_name('milestone'), 'get_milestoneform_elements', $milestone);
        $elements['submit'] = array(
            'type' => 'submitcancel',
            'value' => array(get_string('savemilestone','artefact.milestones'), get_string('cancel')),
            'goto' => get_config('wwwroot') . 'artefact/milestones/index.php',
        );
        $milestoneform = array(
            'name' => empty($milestone) ? 'addmilestone' : 'editmilestone',
            'plugintype' => 'artefact',
            'pluginname' => 'fact',
            'validatecallback' => array(generate_artefact_class_name('milestone'),'validate'),
            'successcallback' => array(generate_artefact_class_name('milestone'),'submit'),
            'elements' => $elements,
        );

        return pieform($milestoneform);
    }

    /**
    * Gets the new/edit fields for the milestone pieform
    *
    */
    public static function get_milestoneform_elements($milestone) {
        $elements = array(
            'title' => array(
                'type' => 'text',
                'defaultvalue' => null,
                'title' => get_string('title', 'artefact.milestones'),
                'size' => 30,
                'rules' => array(
                    'required' => true,
                ),
            ),
            'description' => array(
                'type'  => 'textarea',
                'rows' => 10,
                'cols' => 50,
                'resizable' => false,
                'defaultvalue' => null,
                'title' => get_string('description', 'artefact.milestones'),
            ),
            'tags'        => array(
                'type'        => 'tags',
                'title'       => get_string('tags'),
                'description' => get_string('tagsdescprofile'),
            ),
        );

        if (!empty($milestone)) {
            foreach ($elements as $k => $element) {
                $elements[$k]['defaultvalue'] = $milestone->get($k);
            }
            $elements['milestone'] = array(
                'type' => 'hidden',
                'value' => $milestone->id,
            );
        }

        if (get_config('licensemetadata')) {
            $elements['license'] = license_form_el_basic($milestone);
            $elements['license_advanced'] = license_form_el_advanced($milestone);
        }

        return $elements;
    }

    public function render_self($options) {
        $limit = !isset($options['limit']) ? 3 : (int) $options['limit'];
        $offset = isset($options['offset']) ? intval($options['offset']) : 0;

        $facts = ArtefactTypeFact::get_facts($this->id, $offset, $limit);

        $template = 'artefact:milestones:factrows.tpl';

        $baseurl = get_config('wwwroot') . 'artefact/artefact.php?artefact=' . $this->id;
        if (!empty($options['viewid'])) {
            $baseurl .= '&view=' . $options['viewid'];
        }

        $pagination = array(
            'baseurl' => $baseurl,
            'id' => 'fact_pagination',
            'datatable' => 'facttable',
            'jsonscript' => 'artefact/milestones/viewfacts.json.php',
        );

        ArtefactTypeFact::render_facts($facts, $template, $options, $pagination);

        $smarty = smarty_core();
        $smarty->assign_by_ref('facts', $facts);
        if (isset($options['viewid'])) {
            $smarty->assign('artefacttitle', '<a href="' . $baseurl . '">' . hsc($this->get('title')) . '</a>');
        }
        else {
            $smarty->assign('artefacttitle', hsc($this->get('title')));
        }
        $smarty->assign('milestone', $this);

        if (!empty($options['details']) and get_config('licensemetadata')) {
            $smarty->assign('license', render_license($this));
        }
        else {
            $smarty->assign('license', false);
        }
        $smarty->assign('owner', $this->get('owner'));
        $smarty->assign('tags', $this->get('tags'));

        return array('html' => $smarty->fetch('artefact:milestones:viewmilestone.tpl'), 'javascript' => '');
    }

    public static function is_countable_progressbar() {
        return true;
    }
}

class ArtefactTypeFact extends ArtefactType {

    protected $completed = 0;
    protected $completiondate;

    /**
     * We override the constructor to fetch the extra data.
     *
     * @param integer
     * @param object
     */
    public function __construct($id = 0, $data = null) {
        parent::__construct($id, $data);

        if ($this->id) {
            if ($pdata = get_record('artefact_milestones_fact', 'artefact', $this->id, null, null, null, null, '*, ' . db_format_tsfield('completiondate'))) {
                foreach($pdata as $name => $value) {
                    if (property_exists($this, $name)) {
                        $this->$name = $value;
                    }
                }
            }
            else {
                // This should never happen unless the user is playing around with fact IDs in the location bar or similar
                throw new ArtefactNotFoundException(get_string('factdoesnotexist', 'artefact.milestones'));
            }
        }
    }

    public static function get_links($id) {
        return array(
            '_default' => get_config('wwwroot') . 'artefact/milestones/edit/fact.php?id=' . $id,
        );
    }

    public static function get_icon($options=null) {
        global $THEME;
        return $THEME->get_url('images/milestonefact.png', false, 'artefact/milestones');
    }

    public static function is_singular() {
        return false;
    }

    /**
     * This method extends ArtefactType::commit() by adding additional data
     * into the artefact_milestones_fact table.
     *
     */
    public function commit() {
        if (empty($this->dirty)) {
            return;
        }

        // Return whether or not the commit worked
        $success = false;

        db_begin();
        $new = empty($this->id);

        parent::commit();

        $this->dirty = true;

        $completiondate = $this->get('completiondate');
        if (!empty($completiondate)) {
            $date = db_format_timestamp($completiondate);
        }
        $data = (object)array(
            'artefact'  => $this->get('id'),
            'completed' => $this->get('completed'),
            'completiondate' => $date,
        );

        if ($new) {
            $success = insert_record('artefact_milestones_fact', $data);
        }
        else {
            $success = update_record('artefact_milestones_fact', $data, 'artefact');
        }

        db_commit();

        $this->dirty = $success ? false : true;

        return $success;
    }

    /**
     * This function extends ArtefactType::delete() by also deleting anything
     * that's in fact.
     */
    public function delete() {
        if (empty($this->id)) {
            return;
        }

        db_begin();
        delete_records('artefact_milestones_fact', 'artefact', $this->id);

        parent::delete();
        db_commit();
    }

    public static function bulk_delete($artefactids) {
        if (empty($artefactids)) {
            return;
        }

        $idstr = join(',', array_map('intval', $artefactids));

        db_begin();
        delete_records_select('artefact_milestones_fact', 'artefact IN (' . $idstr . ')');
        parent::bulk_delete($artefactids);
        db_commit();
    }


    /**
    * Gets the new/edit facts pieform
    *
    */
    public static function get_form($parent, $fact=null) {
        require_once(get_config('libroot') . 'pieforms/pieform.php');
        require_once('license.php');
        $elements = call_static_method(generate_artefact_class_name('fact'), 'get_factform_elements', $parent, $fact);
        $elements['submit'] = array(
            'type' => 'submitcancel',
            'value' => array(get_string('savefact','artefact.milestones'), get_string('cancel')),
            'goto' => get_config('wwwroot') . 'artefact/milestones/milestone.php?id=' . $parent,
        );
        $factform = array(
            'name' => empty($fact) ? 'addfacts' : 'editfact',
            'plugintype' => 'artefact',
            'pluginname' => 'fact',
            'validatecallback' => array(generate_artefact_class_name('fact'),'validate'),
            'successcallback' => array(generate_artefact_class_name('fact'),'submit'),
            'elements' => $elements,
        );

        return pieform($factform);
    }

    /**
    * Gets the new/edit fields for the facts pieform
    *
    */
    public static function get_factform_elements($parent, $fact=null) {
        $elements = array(
            'title' => array(
                'type' => 'text',
                'defaultvalue' => null,
                'title' => get_string('title', 'artefact.milestones'),
                'description' => get_string('titledesc','artefact.milestones'),
                'size' => 30,
                'rules' => array(
                    'required' => true,
                ),
            ),
            'completiondate' => array(
                'type'       => 'calendar',
                'caloptions' => array(
                    'showsTime'      => false,
                    'ifFormat'       => '%Y/%m/%d',
                    'dateFormat'     => 'yy/mm/dd',
                ),
                'defaultvalue' => null,
                'title' => get_string('completiondate', 'artefact.milestones'),
                'description' => get_string('dateformatguide'),
                'rules' => array(
                    'required' => true,
                ),
            ),
            'description' => array(
                'type'  => 'textarea',
                'rows' => 10,
                'cols' => 50,
                'resizable' => false,
                'defaultvalue' => null,
                'title' => get_string('description', 'artefact.milestones'),
            ),
            'tags'        => array(
                'type'        => 'tags',
                'title'       => get_string('tags'),
                'description' => get_string('tagsdescprofile'),
            ),
            'completed' => array(
                'type' => 'switchbox',
                'switchtext' => 'yesno',
                'defaultvalue' => null,
                'title' => get_string('completed', 'artefact.milestones'),
                'description' => get_string('completeddesc', 'artefact.milestones'),
            ),
        );

        if (!empty($fact)) {
            foreach ($elements as $k => $element) {
                $elements[$k]['defaultvalue'] = $fact->get($k);
            }
            $elements['fact'] = array(
                'type' => 'hidden',
                'value' => $fact->id,
            );
        }
        if (get_config('licensemetadata')) {
            $elements['license'] = license_form_el_basic($fact);
            $elements['license_advanced'] = license_form_el_advanced($fact);
        }

        $elements['parent'] = array(
            'type' => 'hidden',
            'value' => $parent,
        );

        return $elements;
    }

    public static function validate(Pieform $form, $values) {
        global $USER;
        if (!empty($values['fact'])) {
            $id = (int) $values['fact'];
            $artefact = new ArtefactTypeFact($id);
            if (!$USER->can_edit_artefact($artefact)) {
                $form->set_error('submit', get_string('canteditdontownfact', 'artefact.milestones'));
            }
        }
    }

    public static function submit(Pieform $form, $values) {
        global $USER, $SESSION;

        if (!empty($values['fact'])) {
            $id = (int) $values['fact'];
            $artefact = new ArtefactTypeFact($id);
        }
        else {
            $artefact = new ArtefactTypeFact();
            $artefact->set('owner', $USER->get('id'));
            $artefact->set('parent', $values['parent']);
        }

        $artefact->set('title', $values['title']);
        $artefact->set('description', $values['description']);
        $artefact->set('completed', $values['completed'] ? 1 : 0);
        $artefact->set('completiondate', $values['completiondate']);
        if (get_config('licensemetadata')) {
            $artefact->set('license', $values['license']);
            $artefact->set('licensor', $values['licensor']);
            $artefact->set('licensorurl', $values['licensorurl']);
        }
        $artefact->set('tags', $values['tags']);
        $artefact->commit();

        $SESSION->add_ok_msg(get_string('milestonesavedsuccessfully', 'artefact.milestones'));

        redirect('/artefact/milestones/milestone.php?id='.$values['parent']);
    }

    /**
     * This function returns a list of the current milestones facts.
     *
     * @param limit how many facts to display per page
     * @param offset current page to display
     * @return array (count: integer, data: array)
     */
    public static function get_facts($milestone, $offset=0, $limit=3) {
        $datenow = time(); // time now to use for formatting facts by completion

        ($results = get_records_sql_array("
            SELECT a.id, at.artefact AS fact, at.completed, ".db_format_tsfield('completiondate').",
                a.title, a.description, a.parent, a.owner
                FROM {artefact} a
            JOIN {artefact_milestones_fact} at ON at.artefact = a.id
            WHERE a.artefacttype = 'fact' AND a.parent = ?
            ORDER BY at.completiondate ASC, a.id", array($milestone), $offset, $limit))
            || ($results = array());

        // format the date and setup completed for display if fact is incomplete
        if (!empty($results)) {
            foreach ($results as $result) {
                if (!empty($result->completiondate)) {
                    // if record hasn't been completed and completiondate has passed mark as such for display
                    if ($result->completiondate < $datenow && !$result->completed) {
                        $result->completed = -1;
                    }
                    $result->completiondate = format_date($result->completiondate, 'strftimedate');
                }
                $result->description = '<p>' . preg_replace('/\n\n/','</p><p>', $result->description) . '</p>';
                $result->tags = ArtefactType::artefact_get_tags($result->id);
            }
        }

        $dateFormat = get_string('dateFormat', 'blocktype.milestones/milestones');

        $result = array(
            'count'  => count_records('artefact', 'artefacttype', 'fact', 'parent', $milestone),
            'data'   => $results,
            'offset' => $offset,
            'limit'  => $limit,
            'id'     => $milestone,
            'dateFormat' => $dateFormat,
        );

        return $result;
    }

    public static function get_allfacts($blockid, $offset=0, $limit=3) {
        $get_user = "
            SELECT owner
            FROM block_instance as a
            JOIN view as b
            ON b.id = a.view
            WHERE a.id = ?";
        $result_user = get_records_sql_array($get_user, array($blockid));
        $id_user = intval($result_user[0]->owner);

        $datenow = time(); // time now to use for formatting facts by completion

        ($results = get_records_sql_array("
            SELECT a.id, at.artefact AS fact, at.completed, ".db_format_tsfield('completiondate').",
                a.title, a.description, a.parent, a.owner
                FROM {artefact} a
            JOIN {artefact_milestones_fact} at ON at.artefact = a.id
            WHERE a.artefacttype = 'fact'
            AND a.owner = ?
            ORDER BY at.completiondate ASC, a.id", array($id_user)))
        || ($results = array());

        $count_records = count($results);
        $results = array_slice($results, $offset, $limit + $offset);

        // format the date and setup completed for display if fact is incomplete
        if (!empty($results)) {
            foreach ($results as $result) {
                if (!empty($result->completiondate)) {
                    // if record hasn't been completed and completiondate has passed mark as such for display
                    if ($result->completiondate < $datenow && !$result->completed) {
                        $result->completed = -1;
                    }
                    $result->completiondate = format_date($result->completiondate, 'strftimedate');
                }
                $result->description = '<p>' . preg_replace('/\n\n/','</p><p>', $result->description) . '</p>';
                $result->tags = ArtefactType::artefact_get_tags($result->id);
            }
        }

        $dateFormat = get_string('dateFormat', 'blocktype.milestones/allfacts');

        $result = array(
            'count'  => $count_records,
            'data'   => $results,
            'offset' => $offset,
            'limit'  => $limit,
            'id'     => $blockid,
            'dateFormat' => $dateFormat,
        );

        return $result;
    }

    /**
     * Builds the facts list table for current milestone
     *
     * @param facts (reference)
     */
    public function build_facts_list_html(&$facts) {
        $smarty = smarty_core();
        $smarty->assign_by_ref('facts', $facts);
        $facts['tablerows'] = $smarty->fetch('artefact:milestones:factslist.tpl');
        $pagination = build_pagination(array(
            'id' => 'factlist_pagination',
            'class' => 'center',
            'url' => get_config('wwwroot') . 'artefact/milestones/milestone.php?id='.$facts['id'],
            'jsonscript' => 'artefact/milestones/facts.json.php',
            'datatable' => 'factslist',
            'count' => $facts['count'],
            'limit' => $facts['limit'],
            'offset' => $facts['offset'],
            'firsttext' => '',
            'previoustext' => '',
            'nexttext' => '',
            'lasttext' => '',
            'numbersincludefirstlast' => false,
            'resultcounttextsingular' => get_string('fact', 'artefact.milestones'),
            'resultcounttextplural' => get_string('facts', 'artefact.milestones'),
        ));
        $facts['pagination'] = $pagination['html'];
        $facts['pagination_js'] = $pagination['javascript'];
    }

    /**
     * Function to append the rendered html to the $facts data object
     *
     * @param   array   $facts      The facts array containing fact objects + pagination count data
     * @param   string  $template   The name of the template to use for rendering
     * @param   array   $options    The block instance options
     * @param   array   $pagination The pagination data
     *
     * @return  array   $facts      The facts array updated with rendered table html
     */
    public function render_facts(&$facts, $template, $options, $pagination) {

        $smarty = smarty_core();
        $smarty->assign_by_ref('facts', $facts);
        $smarty->assign_by_ref('options', $options);
        $facts['tablerows'] = $smarty->fetch($template);

        if ($facts['limit'] && $pagination) {
            $pagination = build_pagination(array(
                'id' => $pagination['id'],
                'class' => 'center',
                'datatable' => $pagination['datatable'],
                'url' => $pagination['baseurl'],
                'jsonscript' => $pagination['jsonscript'],
                'count' => $facts['count'],
                'limit' => $facts['limit'],
                'offset' => $facts['offset'],
                'numbersincludefirstlast' => false,
                'resultcounttextsingular' => get_string('fact', 'artefact.milestones'),
                'resultcounttextplural' => get_string('facts', 'artefact.milestones'),
            ));
            $facts['pagination'] = $pagination['html'];
            $facts['pagination_js'] = $pagination['javascript'];
        }
    }

    public static function is_countable_progressbar() {
        return true;
    }
}
