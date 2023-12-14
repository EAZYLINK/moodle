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
 * @package local_esupervision
 */

require_once(__DIR__ . "/../../config.php");
require_login();


$PAGE->set_url(new moodle_url('/local/esupervision/editor.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Editor');
$PAGE->set_heading('Editor');
$PAGE->set_pagelayout('standard');
$PAGE->set_pagetype('editor');

$editoroptions = array('maxfiles' => 1, 'maxbytes' => 10485760, 'context' => context_system::instance(), 'trusttext' => false, 'enable_filemanagement' => true, 'noclean' => true, 'enable_upload' => true, 'editor_height' => 400, 'editor_width' => '100%');

$mform = new \local_esupervision\form\editor_form(null, array('editoroptions' => $editoroptions));

$OUTPUT->header();
$mform->display();
$OUTPUT->footer();