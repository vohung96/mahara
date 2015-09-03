<?php
/**
 *
 * @package    mahara
 * @subpackage blocktype-eselmasearchgroup
 *
 */

defined('INTERNAL') || die();

class PluginBlocktypeEselmasearchgroup extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.internal/eselmasearchgroup');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.internal/eselmasearchgroup');
    }

    public static function get_categories() {
        return array('internal' => 26750);
    }

    public static function render_instance(BlockInstance $instance, $editing=false) {
        require_once(get_config('docroot') . 'artefact/lib.php');
        global $USER;
        $smarty = smarty_core();

        $filter = param_alpha('filter', 'canjoin');
        $query = param_variable('query', '');

        // check that the filter is valid, if not default to 'all'
        if (in_array($filter, array('member', 'notmember', 'canjoin'))) {
            $type = $filter;
        }
        else { // all or some other text
            $filter = 'all';
            $type = 'all';
        }

        $filter_elements = array();
        $filter_elements['query'] = array(
                        'title' => get_string('search'),
                        'defaultvalue' => $query);
        $filter_elements['filter'] = array(
                        'title' => get_string('filter'),
                        'options' => array(
                            'canjoin'   => get_string('groupsicanjoin', 'group'),
                            'notmember' => get_string('groupsnotin', 'group'),
                            'member'    => get_string('groupsimin', 'group'),
                            'all'       => get_string('allgroups', 'group')
                        ),
                        'defaultvalue' => $filter);
        $filter_elements['search'] = array(
                        'type' => 'submit',
                        'value' => get_string('search'));
        $smarty->assign('filter_elements', $filter_elements);
        return $smarty->fetch('blocktype:eselmasearchgroup:content.tpl');
    }

    public static function artefactchooser_element($default=null) {
    }

}
