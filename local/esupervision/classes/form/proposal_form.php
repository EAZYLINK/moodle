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

/**
 * Feedback form for proposal supervision
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package local_esupervision
 */

require_once("$CFG->libdir/formslib.php");
class proposal_form extends \moodleform
{
    public function definition()
    {
        $editoroptions = $this->_customdata['editoroptions'];
        $mform = $this->_form;
        $mform->addElement("header", "header", "Project Proposal", null, false);
        $mform->addElement('text', 'title', 'Proposal Title:');
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', 'required', 'required', null, 'client');
        $mform->addElement('editor', 'content', 'Proposal details', $editoroptions);
        $mform->setType('content', PARAM_RAW);
        $mform->addRule('content', 'Proposal content cannot be empty', 'required', null, 'client');
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
        return $errors;
    }
}