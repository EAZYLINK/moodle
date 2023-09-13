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
 * Feedback form for project supervision
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package course
 */

 require_once(__DIR__ . '/../../../config.php');
 require_once(__DIR__ . '/../../../lib/formslib.php');
 class local_esupervision_submission_form extends moodleform {
    public function definition() {
        $mform = $this->_form;
        $mform->addElement('textarea', 'project_description', 'Project Description:', ['rows' => '5', 'cols' => '50']);
        $mform->addRule('project_description', null, 'required', null, 'client');
        $mform->addElement('filepicker', 'project_document', 'Project Docoment:', null, ['maxbytes' => 1000000, 'accepted_types' => '*']);
        $mform->setType('project_document', PARAM_FILE);
        $mform->addRule('project_document', 'required', 'required', null, 'client');
        $this->add_action_buttons($cancel = true, $submitlabel='submit project');          
    }
    
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if (empty($data['project_description'])) {
            $errors['project_description'] = get_string('error_required', 'local_esupervision');
        } elseif (!empty($files['project_document']['name'])) {
            $fileinfo = $files['project_document'];
            if ($fileinfo['error'] != UPLOAD_ERR_OK) {
                $errors['project_document'] = "failed to upload document";
            }
        }
        return $errors;
    }
 }