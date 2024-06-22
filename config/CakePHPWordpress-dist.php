<?php

// This file goes to your APP/config folder

return [
    'CakePHPWordpress' => [ // Must match the name of this plugin
        'blogList' => [
            'ABC' => [ // Arbitrary UPPERCASE key identifying this configuration
                'name' => 'ABC Blog', // Display name for this blog
                 // Datasource name from APP/config/app_local.php
                'datasource' => 'wordpress', // used to connect to database
                'type' => 'Wordpress6', // currently Wordpress6
                 // Local overrides expected in APP/src/Model/(Entity|Table)/$localPath
                'localPath' => 'CakePHPWordpress', // can be any value
            ],
        ],
    ],
];
