<?php



$ADMIN->add('root', new admin_externalpage('esupervision', get_string('pluginname', 'local_esupervision'), "$CFG->wwwroot/local/esupervision/admin/index.php"));
$ADMIN->add('root', new admin_category('esupervision', get_string('pluginname', 'local_esupervision')));
$ADMIN->add('esupervision', new admin_externalpage('supervisor', 'Supervisor Dashboard', "$CFG->wwwroot/local/esupervision/supervisor/index.php"));
$ADMIN->add('esupervision', new admin_externalpage('student', 'Student Dashboard', "$CFG->wwwroot/local/esupervision/student/index.php"));
