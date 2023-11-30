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

/**
 * project creation form for project supervision
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package local_esupervision
 */

namespace local_esupervision\form;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");


class Create_project_form extends \moodleform
{
    public function definition()
    {
        $mform = $this->_form;
        $mform->addElement('text', 'title', 'Project Title:');
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', 'required', 'required', null, 'client');
        $mform->addElement('textarea', 'description', 'Project Descrption:');
        $mform->setType('description', PARAM_TEXT);
        $mform->addRule('description', 'required', 'required', null, 'client');
        $mform->addElement('text', 'supervisor_name', 'Project Supervisor:');
        $mform->setType('supervisor_name', PARAM_TEXT);
        $mform->addRule('supervisor_name', 'required', 'required', null, 'client');
        $mform->addElement('text', 'student_name', 'Project Student:');
        $mform->setType('student_name', PARAM_TEXT);
        $mform->addRule('student_name', 'required', 'required', null, 'client');
        $this->add_action_buttons(true, 'submit project');
    }
    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);
        return $errors;
    }
}