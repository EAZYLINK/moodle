<?php
// This file is part of your local plugin
// Make sure it is placed in the 'db' folder within your plugin directory

defined('MOODLE_INTERNAL') || die();

function xmldb_local_esupervision_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2023021703) {
        // Code to create or modify tables for version YYYYMMDD00
        // Example: $table = new xmldb_table('your_table');
        //          $table->add_field('id', XMLDB_TYPE_INTEGER, '10', ...);
        //          $table->add_field('name', XMLDB_TYPE_TEXT, '255', ...);
        //          ...

        // Add or modify the database elements as needed

        // Upgrade to next version
        $dbman->upgrade_plugin('local_esupervision', 2023021703);
    }

    if ($oldversion < 2023021704) {
        // Code to create or modify tables for version YYYYMMDD01
        $dbman->upgrade_plugin('local_esupervision', 2023021704);
    }

    return true;
}
