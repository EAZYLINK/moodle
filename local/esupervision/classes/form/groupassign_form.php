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
class groupassign_form extends \moodleform
{
    public function definition()
    {
        $groups = $this->_customdata['groups'];
        $students = $this->_customdata['students'];
        $mform = $this->_form;
        $mform->addElement('select', 'group', 'Group', $groups);
        $mform->addRule('group', null, 'required', null, 'client');
        $mform->setType('group', PARAM_NOTAGS);
        $mform->addElement('select', 'student', 'Select Student', $students);
        $mform->addRule('student', null, 'required', null, 'client');
        $mform->setType('student', PARAM_NOTAGS);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $this->add_action_buttons(true, 'Submit');
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