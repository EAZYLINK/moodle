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
require_once($CFG->libdir.'/formslib.php');

/**
 * Class search_form
 *
 * @package    local_esupervision
 * @copyright  2023 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class search_form extends moodleform{
    public function definition() {
        $mform = this->_form;
    $mform->addElement('text', 'search', 'Search');
    $mform->setType('search', PARAM_TEXT);
    $mform->addElement('submit', 'submitbutton', 'Search');
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if (empty($data['search'])) {
            $errors['search'] = get_string('error_required', 'local_esupervision');
        }
        return $errors;
    }


}