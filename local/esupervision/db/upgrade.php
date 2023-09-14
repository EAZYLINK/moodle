function xmldb_qtype_myqtype_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();
 
    if ($oldversion < 2015031200) {
        if (!$dbman->table_exists($table)) {
           $dbman->create_table($table);
       }
        upgrade_plugin_savepoint(true, 2015031200, 'qtype', 'myqtype');
    }

    return true;
}