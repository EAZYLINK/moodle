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
 * Class grading_form
 *
 * @package    local_esupervision
 * @copyright  2023 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class local_esupervision_grading_form extends moodleform {
   public function definition() {
    $mform = $this->_form;
    $attributes = array(
        'Excellent' => 20,
        'Good' => 15,
        'Average' => 10,
        'Poor' => 5
    );
    $mform->addElement('header', 'header', 'Grading Form');
    $mform->addElement('text', 'student_id',  get_string('student_id', 'local_esupervision'), 'maxlength="100" size="30"');
    $mform->addRule('student_id', null, 'required', null, 'client');
    $mform->setType('student_id', PARAM_NOTAGS);
    $mform->addElement('select',  'attendance',  get_string('attendance', 'local_esupervision'),  $attributes);
    $mform->addRule('attendance', null, 'required', null, 'client');
    $mform->setType('attendance', PARAM_NOTAGS);
    $mform->addElement('select', 'punctuality',  get_string('punctuality', 'local_esupervision'),  $attributes);
    $mform->addRule('punctuality', null, 'required', null, 'client');
    $mform->setType('punctuality', PARAM_NOTAGS);
    $mform->addElement('select', 'attention_to_instruction',  get_string('attention', 'local_esupervision'),  $attributes);
    $mform->addRule('attention_to_instruction', null, 'required', null, 'client');
    $mform->setType('attention_to_instruction', PARAM_NOTAGS);
    $mform->addElement('select', 'turnover_to_work',  get_string('turnover', 'local_esupervision'),  $attributes);
    $mform->addRule('turnover_to_work', null, 'required', null, 'client');
    $mform->setType('turnover_to_work', PARAM_NOTAGS);
    $mform->addElement('select', 'resourcefulness',  get_string('resourcefulness', 'local_esupervision'),  $attributes);
    $mform->addRule('resourcefulness', null, 'required', null, 'client');
    $mform->setType('resourcefulness', PARAM_NOTAGS);
    $mform->addElement('select', 'attitude_to_work',  get_string('attitude', 'local_esupervision'),  $attributes);
    $mform->addRule('attitude_to_work', null, 'required', null, 'client');
    $mform->setType('attitude_to_work', PARAM_NOTAGS);
    $mform->addElement('textarea', 'comment',  get_string('comment', 'local_esupervision'), 'wrap="virtual" rows="10" cols="5"');
    $mform->addRule('comment', null, 'required', null, 'client');
    $mform->setType('comment', PARAM_NOTAGS);
    $this->add_action_buttons();
   } 

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if (empty($data['student_id'])) {
            $errors['student_id'] = get_string('error_required', 'local_esupervision');
        }
        if (empty($data['attendance'])) {
            $errors['attendance'] = get_string('error_required', 'local_esupervision');
        }
        if (empty($data['punctuality'])) {
            $errors['punctuality'] = get_string('error_required', 'local_esupervision');
        }
        if (empty($data['attention_to_instruction'])) {
            $errors['attention_to_instruction'] = get_string('error_required', 'local_esupervision');
        }
        if (empty($data['turnover_to_work'])) {
            $errors['turnover_to_work'] = get_string('error_required', 'local_esupervision');
        }
        if (empty($data['resourcefulness'])) {
            $errors['resourcefulness'] = get_string('error_required', 'local_esupervision');
        }
        if (empty($data['attitude_to_work'])) {
            $errors['attitude_to_work'] = get_string('error_required', 'local_esupervision');
        }
        if (empty($data['comment'])) {
            $errors['comment'] = get_string('error_required', 'local_esupervision');
        }
        return $errors;
    }
}