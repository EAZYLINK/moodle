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
 * TODO describe file observer.php
 *
 * @package    local_esupervision
 * @copyright  2023 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('MOODLE_INTERNAL') || die();

class local_esupervision_observer
{
    /**
     * Event handler for chat message sent
     * 
     * @param \mod_chat\event\message_sent $event The chat message event object
     * @return void
     */

    public static function message_sent(\mod_chat\event\message_sent $event)
    {
        global $DB;

        $eventdata = $event->get_data();

        $record = new stdClass();
        $record->chatid = $eventdata['other']['chatid'];
        $record->userid = $eventdata['userid'];
        $record->message = $eventdata['other']['message'];
        $record->timestamp = $eventdata['timecreated'];

        $record->id = $DB->insert_record('esupervision_chats', $record);

        return $record->id;
    }
}