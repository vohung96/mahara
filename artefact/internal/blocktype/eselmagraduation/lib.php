<?php

/**
 *
 * @package    mahara
 * @subpackage blocktype-graduation
 *
 */
defined('INTERNAL') || die();

class PluginBlocktypeEselmagraduation extends PluginBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.internal/eselmagraduation');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.internal/eselmagraduation');
    }

    public static function get_categories() {
        return array('general' => 24000);
    }

    public function can_have_attachments() {
        return true;
    }

    public static function render_instance(BlockInstance $instance, $editing = false) {
        $configdata = $instance->get('configdata');

        if (!empty($configdata['artefactid'])) {
            safe_require('artefact', 'file');
            safe_require('artefact', 'comment');

            $artefact = $instance->get_artefact_instance($configdata['artefactid']);
            $viewid = $instance->get('view');
            $text = ArtefactTypeFolder::append_view_url($artefact->get('note'), $viewid);
            $information = ArtefactTypeFolder::append_view_url($artefact->get('description'), $viewid);
            $literature = $configdata['literature'];

            $smarty = smarty_core();
            $smarty->assign('text', $text);
            $smarty->assign('information', $information);
            $smarty->assign('literature', $literature);

            $attachments = $artefact->get_attachments();
            if ($attachments) {
                require_once(get_config('docroot') . 'artefact/lib.php');
                foreach ($attachments as &$attachment) {
                    $f = artefact_instance_from_id($attachment->id);
                    $attachment->size = $f->describe_size();
                    $attachment->iconpath = $f->get_icon(array('id' => $attachment->id, 'viewid' => isset($options['viewid']) ? $options['viewid'] : 0));
                    $attachment->viewpath = get_config('wwwroot') . 'artefact/artefact.php?artefact=' . $attachment->id . '&view=' . (isset($viewid) ? $viewid : 0);
                    $attachment->downloadpath = get_config('wwwroot') . 'artefact/file/download.php?file=' . $attachment->id;
                    if (isset($viewid)) {
                        $attachment->downloadpath .= '&view=' . $viewid;
                    }
                }
                $smarty->assign('attachments', $attachments);
                $smarty->assign('count', count($attachments));
            }
            require_once(get_config('docroot') . 'lib/view.php');
            $view = new View($viewid);
            list($commentcount, $comments) = ArtefactTypeComment::get_artefact_comments_for_view($artefact, $view, $instance->get('id'), true, $editing);
            $smarty->assign('commentcount', $commentcount);
            $smarty->assign('comments', $comments);
            $smarty->assign('blockid', $instance->get('id'));
            return $smarty->fetch('blocktype:eselmagraduation:content.tpl');
        }

        return '';
    }

    /**
     * Returns a list of artefact IDs that are in this blockinstance.
     *
     * People may embed artefacts as images etc. They show up as links to the
     * download script, which isn't much to go on, but should be enough for us
     * to detect that the artefacts are therefore 'in' this blocktype.
     */
    public static function get_artefacts(BlockInstance $instance) {
        $configdata = $instance->get('configdata');
        $artefacts = array();
        if (isset($configdata['artefactid'])) {
            $artefacts[] = $configdata['artefactid'];

            // Add all artefacts found in the text
            $text = $instance->get_artefact_instance($configdata['artefactid'])->get('note');
            $information = $instance->get_artefact_instance($configdata['artefactid'])->get('description');
            $artefacts = array_unique(array_merge($artefacts, artefact_get_references_in_html($text)));
            $artefacts = array_unique(array_merge($artefacts, artefact_get_references_in_html($information)));
        }
        return $artefacts;
    }

    public static function artefactchooser_element($default = null) {
        return array(
            'name' => 'artefactid',
            'type' => 'artefactchooser',
            'class' => 'hidden',
            'defaultvalue' => $default,
            'blocktype' => 'eselmagraduation',
            'limit' => 5,
            'selectone' => true,
            'selectjscallback' => 'updateTextContent',
            'getblocks' => true,
            'ownerinfo' => true,
            'returnfields' => array('id', 'title', 'description', 'safedescription', 'editable', 'attachments'),
            'artefacttypes' => array('html'),
            'template' => 'artefact:internal:html-artefactchooser-element.tpl',
        );
    }

    public static function artefactchooser_get_element_data($artefact) {
        return $artefact;
    }

    public static function get_instance_config_javascript(BlockInstance $instance) {
        // When an artefact is selected in the artefactchooser, update the
        // contents of the wysiwyg editor and the message about the number
        // of blocks containing the new artefact.
        $blockid = $instance->get('id');
        return <<<EOF
function updateTextContent(a) {
    setNodeAttribute('instconf_title', 'value', a.title);
    if (typeof(tinyMCE) != 'undefined') {
        tinyMCE.activeEditor.setContent(a.description);
    }
    jQuery('#instconf_textreadonly_display').innerHTML = a.safedescription;
    jQuery('#instconf_makecopy').prop('checked', false);
    if (a.editable == 1) {
        addElementClass('instconf_textreadonly_header', 'hidden');
        addElementClass('instconf_textreadonly_container', 'hidden');
        addElementClass('instconf_readonlymsg_container', 'hidden');
        removeElementClass('instconf_text_header', 'hidden');
        removeElementClass('instconf_text_container', 'hidden');
        var blockcountmsg = jQuery('#instconf_otherblocksmsg_container');
        if (blockcountmsg && jQuery('#textbox_blockcount')) {
            var otherblockcount = 0;
            if (a.blocks && a.blocks.length > 0) {
                for (var i = 0; i < a.blocks.length; i++) {
                    if (a.blocks[i] != {$blockid}) {
                        otherblockcount++;
                    }
                }
            }
            if (otherblockcount) {
                replaceChildNodes('textbox_blockcount', otherblockcount);
                jQuery(blockcountmsg).removeClass('hidden');
            }
            else {
                jQuery(blockcountmsg).addClass('hidden');
            }
        }

        if (typeof a.attachments != 'undefined') {
            // remove any attached files
            for (var key in instconf_artefactids.selecteddata) {
                signal($('instconf_artefactids_unselect_' + key), 'onclick', instconf_artefactids.unselect);
            }
            // add in ones we need
            if (a.attachments.length > 0) {
                for (var i = 0; i < a.attachments.length; i++) {
                    instconf_artefactids.add_to_selected_list(a.attachments[i]);
                }
            }
        }
    }
    else {
        addElementClass('instconf_text_header', 'hidden');
        addElementClass('instconf_text_container', 'hidden');
        addElementClass('instconf_otherblocksmsg_container', 'hidden');
        removeElementClass('instconf_textreadonly_header', 'hidden');
        removeElementClass('instconf_textreadonly_container', 'hidden');
        removeElementClass('instconf_readonlymsg_container', 'hidden');
    }
}
connect('chooseartefactlink', 'onclick', function(e) {
    e.stop();
    toggleElementClass('hidden', 'instconf_artefactid_container');
    toggleElementClass('hidden', 'instconf_managenotes_container');
});
forEach(getElementsByTagAndClassName('a', 'copytextboxnote', 'instconf'), function(link) {
    connect(link, 'onclick', function(e) {
        e.stop();
        forEach(getElementsByTagAndClassName('input', 'radio', 'artefactid_data'), function(i) {
            if (i.checked) {
                i.checked = false;
            }
        });
        jQuery('#instconf_makecopy').prop('checked', true);
        addElementClass('instconf_textreadonly_header', 'hidden');
        addElementClass('instconf_textreadonly_container', 'hidden');
        addElementClass('instconf_readonlymsg_container', 'hidden');
        addElementClass('instconf_otherblocksmsg_container', 'hidden');
        removeElementClass('instconf_text_header', 'hidden');
        removeElementClass('instconf_text_container', 'hidden');
    });
});
if (jQuery('#instconf_artefactids_upload_browse')) {
    addElementClass('instconf_artefactids_upload_browse', 'hidden');
    removeElementClass('instconf_artefactids_open_upload_browse_container', 'hidden');
}
EOF;
    }

    private static function get_task() {
        global $USER;
        $taskdata = get_records_sql_array("SELECT * FROM {artefact} WHERE artefacttype = 'task' AND owner = $USER->id");
        $data_tmp = array();
        foreach ($taskdata as $value) {
            $data_tmp[$value->id] = $value->title;
        }
        return $data_tmp;
    }

    public static function has_instance_config() {
        return true;
    }

    public static function instance_config_form(BlockInstance $instance) {
        global $USER;
        safe_require('artefact', 'file');
        $instance->set('artefactplugin', 'internal');
        $configdata = $instance->get('configdata');
        if (!$height = get_config('blockeditorheight')) {
            $cfheight = param_integer('cfheight', 0);
            $height = $cfheight ? $cfheight * 0.7 : 150;
        }

        $otherblockcount = 0;
        $readonly = false;
        $text = '';
        $information = '';
        $literature = $configdata['literature'];
        $view = $instance->get_view();

        if (!empty($configdata['artefactid'])) {
            $artefactid = $configdata['artefactid'];
            try {
                $artefact = $instance->get_artefact_instance($artefactid);

                $readonly = $artefact->get('owner') !== $view->get('owner') || $artefact->get('group') !== $view->get('group') || $artefact->get('institution') !== $view->get('institution') || $artefact->get('locked') || !$USER->can_edit_artefact($artefact);

                $text = $artefact->get('note');
                $information = $artefact->get('description');

                if ($blocks = get_column('view_artefact', 'block', 'artefact', $artefactid)) {
                    $blocks = array_unique($blocks);
                    $otherblockcount = count($blocks) - 1;
                }
            } catch (ArtefactNotFoundException $e) {
                unset($artefactid);
            }
        }

        $otherblocksmsg = '<span id="textbox_blockcount">' . $otherblockcount . '</span>';
        $otherblocksmsg = get_string('textusedinotherblocks', 'blocktype.internal/textbox', $otherblocksmsg);

        $manageurl = get_config('wwwroot') . 'artefact/internal/notes.php';
        if ($group = $view->get('group')) {
            $manageurl .= '?group=' . $group;
        } else if ($institution = $view->get('institution')) {
            $manageurl .= '?institution=' . $institution;
        }

        // Update the attached files in block configdata as
        // it may change when attached files have been deleted
        $attachmentids = isset($artefact) ? $artefact->attachment_id_list() : false;
        if ($attachmentids !== false && $configdata['artefactids'] != $attachmentids) {
            $configdata['artefactids'] = $attachmentids;
            $instance->set('configdata', $configdata);
            $instance->commit();
        }

        $elements = array(
            // Add a message whenever this text appears in some other block
            'otherblocksmsg' => array(
                'type' => 'html',
                'class' => 'message info' . (($otherblockcount && !$readonly) ? '' : ' hidden'),
                'value' => $otherblocksmsg,
                'help' => true,
            ),
            // Add a message whenever this text cannot be edited here
            'readonlymsg' => array(
                'type' => 'html',
                'class' => 'message info' . ($readonly ? '' : ' hidden'),
                'value' => get_string('readonlymessage', 'blocktype.internal/textbox'),
                'help' => true,
            ),
            'text' => array(
                'type' => 'wysiwyg',
                'class' => $readonly ? 'hidden' : '',
                'title' => get_string('note', 'blocktype.internal/eselmagraduation'),
                'width' => '100%',
                'height' => $height . 'px',
                'defaultvalue' => $text,
                'rules' => array('maxlength' => 65536),
            ),
            'textreadonly' => array(
                'type' => 'html',
                'class' => $readonly ? '' : 'hidden',
                'title' => get_string('note', 'blocktype.internal/eselmagraduation'),
                'width' => '100%',
                'value' => '<div id="instconf_textreadonly_display">' . $text . '</div>',
            ),
            'makecopy' => array(
                'type' => 'checkbox',
                'class' => 'hidden',
                'defaultvalue' => false,
            ),
            'chooseartefact' => array(
                'type' => 'html',
                'class' => 'nojs-hidden-block',
                'value' => '<a id="chooseartefactlink" href=""></a>',
            ),
            'managenotes' => array(
                'type' => 'html',
                'class' => 'right hidden',
                'value' => '<a href="' . $manageurl . '" target="_blank">'
                . get_string('managealltextboxcontent1', 'blocktype.internal/textbox') . ' &raquo;</a>',
            ),
            'selecttask' => array(
                'type' => 'select',
                'title' => get_string('selecttask', 'view'),
                'description' => get_string('selecttaskdescription', 'view'),
                'options' => PluginBlocktypeEselmagraduation::get_task(),
                'defaultvalue' => $retractable + $retractedonload,
            ),
            'artefactid' => self::artefactchooser_element(isset($artefactid) ? $artefactid : null),
            'literature' => array(
                'type' => 'text',
                'title' => get_string('literature'),
                'defaultvalue' => $literature,
            ),
            'information' => array(
                'type' => 'wysiwyg',
                'class' => $readonly ? 'hidden' : '',
                'title' => get_string('information', 'blocktype.internal/eselmagraduation'),
                'width' => '100%',
                'height' => $height . 'px',
                'defaultvalue' => $information,
                'rules' => array('maxlength' => 65536),
            ),
            'allowcomments' => array(
                'type' => 'switchbox',
                'title' => get_string('allowcomments', 'artefact.comment'),
                'defaultvalue' => (!empty($artefact) ? $artefact->get('allowcomments') : 1),
            ),
            'artefactids' => self::filebrowser_element($instance, (isset($configdata['artefactids'])) ? $configdata['artefactids'] : null),
        );
        return $elements;
    }

    public static function delete_instance($instance) {
        require_once('embeddedimage.php');
        $configdata = $instance->get('configdata');
        if (!empty($configdata)) {
            $artefactid = $configdata['artefactid'];
            if (!empty($artefactid)) {
                EmbeddedImage::delete_embedded_images($instance->get('blocktype'), $artefactid);
            }
        }
    }

    public static function instance_config_save($values, $instance) {
        global $USER;
        require_once('embeddedimage.php');
        $data = array();
        $view = $instance->get_view();
        foreach (array('owner', 'group', 'institution') as $f) {
            $data[$f] = $view->get($f);
        }

        if (empty($values['artefactid']) || $values['makecopy']) {
            // The artefact title will be the same as the block title when the
            // artefact is first created, or, if there's no block title, generate
            // 'Note (1)', 'Note (2)', etc.  After that, the artefact title can't
            // be edited inside the block, but can be changed in the Notes area.
            if (empty($values['title'])) {
                $title = artefact_new_title(
                        get_string('Note', 'artefact.internal'), 'html', $data['owner'], $data['group'], $data['institution']
                );
            } else {
                $title = $values['title'];
            }
            $artefact = new ArtefactTypeHtml(0, $data);
            $artefact->set('title', $title);
            $artefact->set('description', $values['information']);
//            try{
//                $artefact->set('parent', $values['selecttask']);
//            } catch (Exception $ex) {
//                var_dump($ex);
//            }
            
            $artefact->set('note', $values['text']);
            $artefact->set('allowcomments', (!empty($values['allowcomments']) ? $values['allowcomments'] : 0));
        } else {
            $artefact = new ArtefactTypeHtml((int) $values['artefactid']);

            if (!$USER->can_publish_artefact($artefact)) {
                throw new AccessDeniedException(get_string('nopublishpermissiononartefact', 'mahara', hsc($artefact->get('title'))));
            }

            // Stop users from editing textbox artefacts whose owner is not the same as the
            // view owner, even if they would normally be allowed to edit the artefact.
            // It's too confusing.  Textbox artefacts with other owners *can* be included in
            // the view read-only, provided the artefact has the correct republish
            // permission.
            if ($artefact->get('owner') === $data['owner'] && $artefact->get('group') === $data['group'] && $artefact->get('institution') === $data['institution'] && !$artefact->get('locked') && $USER->can_edit_artefact($artefact)) {
                $newnote = EmbeddedImage::prepare_embedded_images($values['text'], 'eselmagraduation', (int) $values['artefactid'], $view->get('group'));
                $artefact->set('note', $newnote);
                $newdescription = EmbeddedImage::prepare_embedded_images($values['information'], 'eselmagraduation', (int) $values['artefactid'], $view->get('group'));
                $artefact->set('description', $newdescription);
                $artefact->set('allowcomments', !empty($values['allowcomments']) ? 1 : 0);
            }
        }
        $artefact->commit();
        $tmp1 = $artefact->get('id');
        $tmp2 = $values['selecttask'];
        if(get_record_sql("SELECT id FROM artefact WHERE id= $tmp2")){
            get_record_sql("UPDATE artefact SET note = $tmp1 WHERE id=$tmp2");
        }
        $newnote = EmbeddedImage::prepare_embedded_images($values['text'], 'eselmagraduation', $artefact->get('id'), $view->get('group'));

        if ($newnote !== $values['text']) {
            $updatedartefact = new stdClass();
            $updatedartefact->id = $artefact->get('id');
            $updatedartefact->note = $newnote;
            update_record('artefact', $updatedartefact, 'id');
        }

        $newdescription = EmbeddedImage::prepare_embedded_images($values['information'], 'eselmagraduation', $artefact->get('id'), $view->get('group'));

        if ($newdescription !== $values['information']) {
            $updatedartefact = new stdClass();
            $updatedartefact->id = $artefact->get('id');
            $updatedartefact->description = $newdescription;
            update_record('artefact', $updatedartefact, 'id');
        }

        // Add attachments, if there are any...
        $old = $artefact->attachment_id_list();
        $new = is_array($values['artefactids']) ? $values['artefactids'] : array();
        // only allow the attaching of files that exist and are editable by user
        foreach ($new as $key => $fileid) {
            $file = artefact_instance_from_id($fileid);
            if (!($file instanceof ArtefactTypeFile) || !$USER->can_publish_artefact($file)) {
                unset($new[$key]);
            }
        }
        if (!empty($new) || !empty($old)) {
            foreach ($old as $o) {
                if (!in_array($o, $new)) {
                    try {
                        $artefact->detach($o);
                    } catch (ArtefactNotFoundException $e) {
                        
                    }
                }
            }
            foreach ($new as $n) {
                if (!in_array($n, $old)) {
                    try {
                        $artefact->attach($n);
                    } catch (ArtefactNotFoundException $e) {
                        
                    }
                }
            }
        }

        $values['artefactid'] = $artefact->get('id');
        $instance->save_artefact_instance($artefact);

        unset($values['text']);
        unset($values['information']);
        unset($values['otherblocksmsg']);
        unset($values['readonlymsg']);
        unset($values['textreadonly']);
        unset($values['makecopy']);
        unset($values['chooseartefact']);
        unset($values['managenotes']);
        unset($values['selecttask']);
        unset($values['allowcomments']);

        // Pass back a list of any other blocks that need to be rendered
        // due to this change.
        $values['_redrawblocks'] = array_unique(get_column(
                        'view_artefact', 'block', 'artefact', $values['artefactid'], 'view', $instance->get('view')
        ));

        return $values;
    }

    public static function filebrowser_element(&$instance, $default = array()) {
        $element = ArtefactTypeFileBase::blockconfig_filebrowser_element($instance, $default);
        $element['title'] = get_string('attachments', 'artefact.blog');
        $element['name'] = 'artefactids';
        $element['config']['selectone'] = false;
        $element['config']['alwaysopen'] = false;
        return $element;
    }

    public static function default_copy_type() {
        return 'full';
    }

    /**
     * The content of this block is now stored as an html artefact, but older versions stored
     * the content directly in the 'text' property of the block config.  If this config has
     * 'text' but not 'artefactid', create an artefact.
     *
     * @param array $biconfig   The block instance config
     * @param array $viewconfig The view config
     * @return BlockInstance The newly made block instance
     */
    public static function import_create_blockinstance_leap(array $biconfig, array $viewconfig) {
        $configdata = $biconfig['config'];

        if (isset($configdata['text']) && !isset($configdata['artefactid'])) {
            $data = array(
                'title' => $biconfig['title'],
                'note' => $configdata['text'],
                'description' => $configdata['information'],
                'owner' => $viewconfig['owner'],
            );
            $artefact = new ArtefactTypeHtml(0, $data);
            $artefact->commit();
            $configdata['artefactid'] = $artefact->get('id');
            unset($configdata['text']);
        }

        $bi = new BlockInstance(0, array(
            'blocktype' => $biconfig['type'],
            'configdata' => $configdata,
                )
        );

        return $bi;
    }

    /**
     * Set the text property of the block config so that exports can be imported
     * into older versions.
     *
     * @param BlockInstance $bi The blockinstance to export the config for.
     * @return array The config for the blockinstance
     */
    public static function export_blockinstance_config_leap(BlockInstance $bi) {
        $configdata = $bi->get('configdata');

        $result = array();

        $text = '';
        $information = '';

        if (!empty($configdata['artefactid'])) {
            $result['artefactid'] = json_encode(array($configdata['artefactid']));
            $note = $bi->get_artefact_instance($configdata['artefactid']);
            $text = $note->get('note');
            $information = $note->get('description');
        }

        $result['text'] = json_encode(array($text));
        $result['information'] = json_encode(array($information));

        return $result;
    }

}
