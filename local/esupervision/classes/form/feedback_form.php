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
 * Feedback form for project supervision
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package local_esupervision_v1
 */


require_once("$CFG->libdir/formslib.php");

class feedback_form extends \moodleform
{
    public function definition()
    {
        $feedbackoptions = $this->_customdata['feedbackoptions'];
        $mform = $this->_form;
        $mform->addElement('header', 'header', 'Feedback', null, false);
        $mform->addElement('editor', 'feedback', 'Feedback:', null, $feedbackoptions);
        $mform->addRule('feedback', 'feedback is required', 'required', null, 'client');
        $mform->setType('feedback', PARAM_RAW);
        $mform->addElement('hidden', 'submissionid');
        $mform->setType('submissionid', PARAM_INT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
    }

    public function setEditorDefault($content, $format)
    {
        $this->_form->setDefault('content', [
            'text' => $content,
            'format' => $format
        ]);
    }

    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);
        if (empty($data['content'])) {
            $errors['content'] = get_string('error_required', 'local_esupervision');
        }
        return $errors;
    }
}

