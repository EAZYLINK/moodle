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
 * admin dashboard
 *
 * @package    core
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../config.php');
require_login();

// Define the page context and set the page heading.
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_heading(get_string('pluginname', 'local_esupervision'));
$PAGE->set_title(get_string('admin_dashboard', 'local_esupervision'));

// Add navigation link to the sidebar.
$PAGE->navbar->add(get_string('admin_dashboard', 'local_esupervision'), "$CFG->wwwroot/local/esupervision/admin/index.php");
$PAGE->navbar->add(get_string('dashboard', 'local_esupervision'), "$CFG->wwwroot/local/esupervision/admin/index.php");

echo $OUTPUT->header();

echo "<h2>Welcome to the Admin Dashboard!</h2>";
echo "<p>As an admin, you can manage projects, users, and other settings here.</p>";

echo $OUTPUT->footer();
