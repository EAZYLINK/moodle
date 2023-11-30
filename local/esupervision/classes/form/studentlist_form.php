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
 * Class studentlist_form
 *
 * @package    local_esupervision
 * @copyright  2023 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");

class studentlist_form extends \moodleform
{
    public function definition()
    {
        $mform = $this->_form;
        $mform->addElement('header', 'header', 'Student List');
        $mform->addElement('text', 'student_id', 'Student ID');
        $mform->setType('student_id', PARAM_TEXT);
        $mform->addRule('student_id', null, 'required', null, 'client');
        $mform->addElement('text', 'supervisor_name', 'Supervisor Name');
        $mform->setType('supervisor_name', PARAM_TEXT);
        $mform->addRule('supervisor_name', null, 'required', null, 'client');
        $mform->addElement('text', 'department', 'Department');
        $mform->setType('department', PARAM_TEXT);
        $mform->addRule('department', null, 'required', null, 'client');
        $this->add_action_buttons(true, 'Add Student');
    }

    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);
        return $errors;
    }
}

class upload_studentlist_form extends \moodleform
{
    public function definition()
    {
        $mform = $this->_form;
        $mform->addElement('header', 'header', 'Upload Student List');
        $mform->addElement(
            'filepicker',
            'student_list',
            'Upload Student List',
            null,
            [
                'subdirs' => 0,
                'areamaxbytes' => 10485760,
                'maxfiles' => 50,
                'accepted_types' => ['csv', 'xls', 'xlsx'],
            ]
        );
        $mform->addRule('student_list', 'required', 'required', null, 'client');
        $mform->setType('student_list', PARAM_FILE);
        $this->add_action_buttons(true, 'Upload Student List');
    }

    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);
        return $errors;
    }
}