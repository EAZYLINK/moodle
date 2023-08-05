<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array (
    'local/esupervision:viewpages' => array (
        'riskbitmask' => RISK_PERSONAL,
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array (
            'manager' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'student' => CAP_ALLOW,
            'guest' => CAP_PREVENT,
        )
    ),

    'local/esupervision:editpages' => array (
        'riskbitmask' => RISK_PERSONAL,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array (
            'manager' => CAP_ALLOW,
            'teacher' => CAP_PREVENT,
            'student' => CAP_PREVENT,
            'guest' => CAP_PREVENT,
        )
    ),

    'local/esupervision:managepages' => array (
        'riskbitmask' => RISK_PERSONAL,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array (
            'manager' => CAP_ALLOW,
            'teacher' => CAP_PREVENT,
            'student' => CAP_PREVENT,
            'guest' => CAP_PREVENT,
        )
    ),
);