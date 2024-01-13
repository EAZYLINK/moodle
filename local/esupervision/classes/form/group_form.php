<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
namespace local_esupervision\form;

defined('MOODLE_INTERNAL') || die();

/**
 * Feedback form for proposal supervision
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package local_esupervision
 */

require_once("$CFG->libdir/formslib.php");
class group_form extends \moodleform
{
    public function definition()
    {
        $editoroptions = $this->_customdata['editoroptions'];
        $userlist = $this->_customdata['userlist'];
        $mform = $this->_form;
        $mform->addElement('text', 'name', 'Group name');
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', 'group name is required', 'required', null, 'client');
        $mform->addElement('select', 'supervisor', 'Supervisor', $userlist);
        $mform->addRule('supervisor', null, 'required', null, 'client');
        $mform->setType('supervisor', PARAM_NOTAGS);
        $mform->addElement('editor', 'description', 'description', $editoroptions);
        $mform->setType('description', PARAM_RAW);
        $mform->addElement('text', 'idnumber', 'Group ID No');
        $mform->setType('idnumber', PARAM_TEXT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $this->add_action_buttons(true, 'create group');
    }

    public function setEditorDefault($description, $format)
    {
        $this->_form->setDefault('description', [
            'text' => $description,
            'format' => $format
        ]);
    }

    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);
        return $errors;
    }
}