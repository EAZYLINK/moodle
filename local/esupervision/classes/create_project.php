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
 * @package course
 */

 require_once(__DIR__ . '/../../../config.php');
 require_once(__DIR__ . '/../../../lib/formslib.php');
 class local_esupervision_create_project_form extends moodleform {
    public function definition() {
        $mform = $this->_form;
        $mform->addElement('text', 'name', 'Project Name:');
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', 'required', 'required', null, 'client');
        $mform->addElement('textarea', 'description', 'Project Descrption:');
        $mform->setType('description', PARAM_TEXT);
        $mform->addRule('description', 'required', 'required', null, 'client');
        $mform->addElement('text', 'supervisor', 'Project Supervisor:');
        $mform->setType('supervisor', PARAM_TEXT);
        $mform->addRule('supervisor', 'required', 'required', null, 'client');
        $mform->addElement('text', 'status', 'Status:');
        $mform->setType('status', PARAM_TEXT);
        $mform->addRule('status', 'required', 'required', null, 'client');
        $this->add_action_buttons($cancel = true, $submitlabel='submit project');          
    }
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }
 }