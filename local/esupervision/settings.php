<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig){

    $ADMIN->add('root', new admin_category('esupervision', get_string('pluginname', 'local_esupervision')));
    $ADMIN->add('esupervision', new admin_externalpage('supervisor', 'Supervisor Dashboard', 
    new moodle_url('$CFG->wwwroot/local/esupervision/supervisor/index.php')));
    $ADMIN->add('esupervision', new admin_externalpage('student', 'Student Dashboard', 
    new moodle_url('/local/esupervision/student/index.php')));
}


