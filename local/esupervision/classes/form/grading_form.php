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
 * Class grading_form
 *
 * @package    local_esupervision
 * @copyright  2023 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once("$CFG->libdir/formslib.php");

class grading_form extends \moodleform
{
    public function definition()
    {
        $mform = $this->_form;
        $attributes = array(
           5 => 'A',
           4 => 'B',
           3 => 'C',
           2 => 'D',
           0 => 'F'
        );
        $studentlist = $this->_customdata['studentlist'];
        $mform->addElement('header', 'header', 'Grading Form: (A = 5, B = 4, C = 3, D = 2, F=0)');
        $mform->addElement('select', 'student', 'student', $studentlist);
        $mform->addRule('student', null, 'required', null, 'client');
        $mform->setType('student', PARAM_NOTAGS);
        $mform->addElement('select', 'attendance', get_string('attendance', 'local_esupervision'), $attributes);
        $mform->addRule('attendance', null, 'required', null, 'client');
        $mform->setType('attendance', PARAM_NOTAGS);
        $mform->addElement('select', 'punctuality', get_string('punctuality', 'local_esupervision'), $attributes);
        $mform->addRule('punctuality', null, 'required', null, 'client');
        $mform->setType('punctuality', PARAM_NOTAGS);
        $mform->addElement('select', 'attentiontoinstruction', get_string('attention', 'local_esupervision'), $attributes);
        $mform->addRule('attentiontoinstruction', null, 'required', null, 'client');
        $mform->setType('attentiontoinstruction', PARAM_NOTAGS);
        $mform->addElement('select', 'turnover', get_string('turnover', 'local_esupervision'), $attributes);
        $mform->addRule('turnover', null, 'required', null, 'client');
        $mform->setType('turnover', PARAM_NOTAGS);
        $mform->addElement('select', 'attitudetowork', get_string('attitude', 'local_esupervision'), $attributes);
        $mform->addRule('attitudetowork', null, 'required', null, 'client');
        $mform->setType('attitudetowork', PARAM_NOTAGS);
        $mform->addElement('select', 'resourcefulness', get_string('resourcefulness', 'local_esupervision'), $attributes);
        $mform->addRule('resourcefulness', null, 'required', null, 'client');
        $mform->setType('resourcefulness', PARAM_NOTAGS);
        $mform->addElement('textarea', 'comment', get_string('comment', 'local_esupervision'), 'wrap="virtual" rows="5" cols="4"');
        $mform->addRule('comment', null, 'required', null, 'client');
        $mform->setType('comment', PARAM_NOTAGS);
        $this->add_action_buttons(true, 'Submit grade');
    }

    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);
        return $errors;
    }
}