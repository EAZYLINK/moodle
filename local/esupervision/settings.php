<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig){
    $ADMIN->add('root', new admin_category('local_esupervision', get_string('pluginname', 'local_esupervision')));
    $ADMIN->add('local_esupervision', new admin_externalpage('supervisor', 'Supervisor Dashboard', 
    new moodle_url($CFG->wwwroot.'/local/esupervision/dashboard/supervisor.php')));
    $ADMIN->add('local_esupervision', new admin_externalpage('student', 'Student Dashboard', 
    new moodle_url($CFG->wwwroot.'/local/esupervision/dashboard/supervisor.php')));
}

