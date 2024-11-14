<?php

// This file goes to your APP/config folder

return [
    'CakePHPWordpress' => [ // Must match the name of this plugin
        'blogList' => [
            'ABC' => [ // Arbitrary UPPERCASE key identifying this configuration
                'name' => 'ABC Blog', // Display name for this blog
                // Datasource name from APP/config/app_local.php
                'datasource' => 'wordpress', // used to connect to database
                // Some installs, particularly multisite networks, prefix tables
                'tablePrefix' => null, // example values: wp_, wp_2_, wp_3_ etc.
                'type' => 'Wordpress6', // currently Wordpress6
                // Local overrides expected in APP/src/Model/(Entity|Table)/$localPath
                'localPath' => 'CakePHPWordpress', // can be any value
                // Your Wordpress content probably relies on some CSS; link here
                /*
                'externalCss' => [
                    // plugin that outputs all CSS that Wordpress would use
                    'http://wordpress.example.com/wp-json/wordpress-export-css/wordpress-export-css.css',
                    // static file; optional 'foo' becomes <link id="externalCss-foo">
                    'foo' => '//wordpress.example.com/wp-includes/css/dist/block-library/style.css',
                ],
                */
            ],
        ],
    ],
];
