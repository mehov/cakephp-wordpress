<?php

/*
 * Pages go first. PageRoute checks if a page exists and returns null if it
 * was not found, allowing request to proceed to check posts.
 *
 * Unlike Pages, Posts just route to viewPost and stop. This can be a problem
 * when posts are at `/` just like the pages (so not prefixed with e.g. `/blog`)
 * because posts will then hijack the `/`, not continuing to check for pages.
 */
$routes->plugin(
    $this->getName(), // name of this plugin
    [
        'path' => '/', // Wordpress pages don't share blog base path with posts
        '_namePrefix' => 'Blog:' // prefix internal names of these routes
    ],
    function ($routes) {
        // Important: use our custom route class for Pages
        $routes->setRouteClass($this->getName().'.PageRoute');
        $routes->connect(
            '/*',
            [
                'plugin' => $this->getName(), 'controller' => 'Posts', 'action' => 'viewPage'
            ],
            [
                '_name' => 'Page', // make accessible as Blog:Page
            ]
        );
    }
);

// Read permalink_structure from Wordpress options preloaded in bootstrap.php
$permalink_structure = \Cake\Core\Configure::read(
    'CakePHPWordpress.Options.permalink_structure'
);
/*
 * Permalink consists of two parts: base path to blog and post identifier. E.g.:
 * - `/blog/%id%`: `/blog/` is base, `%id%` is post
 * - `/%category%/%postname%`: `/` is base, `%category%/%postname%` is post
 *
 * Why is base path to blog important to know?
 *
 * Base path is where we host not only post identifiers, but also blog index
 * where we list all posts, as well as other blog-related URLs, such as:
 * - /blog/author/john-doe
 * - /blog/category/cakephp
 * - /blog/tag/cakephp-5
 *
 * Therefore we need to:
 * 1. split permalink into a. base blog path and b. the rest;
 * 2. define the base blog path as scoped route collection;
 * 3. connect individual blog routes (blog index, individual post etc.) to it.
 */
// Split permalink_structure into array we can loop through
$permalink_structure = explode('/', $permalink_structure);
// Remove empty elements
$permalink_structure = array_filter($permalink_structure);
// Define two main logical parts $permalink_structure consists of
$base_path = null; // base path to blog
$route = null; // route to individual post
// Loop through each piece of our permalink structure
foreach ($permalink_structure as $piece) {
    // If we haven't run into a placeholder yet
    if (strpos($piece, '%') === false && empty($route)) {
        // … keep appending the current piece to $base_path
        $base_path.= '/'.$piece;
    } else {
        // we found a placeholder; switch to populating $route now
        $route.= '/'.preg_replace('/%(\w+)%/', '{$1}', $piece);
    }
}
if (empty($base_path)) {
    $base_path = '/';
}
// Define the main scoped route collection for our blog in this plugin
$routes->plugin(
    $this->getName(), // name of this plugin
    [
        'path' => $base_path, // we are scoping all blog-related URLs
        '_namePrefix' => 'Blog:' // prefix internal names of these routes
    ],
    // Connect individual routes to our blog scope
    function ($routes) use($route) {
        // Sitemap
        $routes->connect(
            '/sitemap',
            [
                'plugin' => $this->getName(), 'controller' => 'Sitemaps', 'action' => 'index'
            ],
            [
                '_name' => 'Sitemap', // make accessible as 'Blog:Sitemap'
                '_ext' => 'xml',
            ]
        );
        // Endpoint for fetching and caching styles configured under externalCss
        $routes->connect(
            '/content-css/{symbol}-{key}',
            [
                'plugin' => $this->getName(), 'controller' => 'Stylesheets', 'action' => 'output'
            ],
            [
                '_name' => 'ContentStylesheet', // make accessible as 'Blog:ContentStylesheet'
                '_ext' => 'css',
                'pass'=> ['symbol', 'key'],
                'symbol' => '[A-Z0-9]+',
                'key' => '[a-zA-Z0-9-_]+',
            ]
        );
        // Blog index
        $routes->connect(
            '/',
            ['plugin' => $this->getName(), 'controller' => 'Posts', 'action' => 'index'],
            ['_name' => 'Posts'] // make accessible as 'Blog:Index'
        );
        // Term Taxonomy: category
        $routes->connect(
            '/category/{slug}', // match Wordpress URL for categories
            ['plugin' => $this->getName(), 'controller' => 'Posts', 'action' => 'routedTermTaxonomy'],
            [
                '_name' => 'category', // full route name is 'Blog:category'
                'pass'=> ['_name', 'slug'],
                'slug' => '[a-zA-Z0-9-_]+',
            ]
        );
        // Term Taxonomy: post_tag
        $routes->connect(
            // WP uses the short 'tag' only in URL, elsewhere it is 'post_tag'
            '/tag/{slug}', // match Wordpress URL for tags
            ['plugin' => $this->getName(), 'controller' => 'Posts', 'action' => 'routedTermTaxonomy'],
            [
                '_name' => 'post_tag', // full route name is 'Blog:post_tag'
                'pass'=> ['_name', 'slug'],
                'slug' => '[a-zA-Z0-9-_]+',
            ]
        );
        // Individual blog post
        $routes->connect(
            $route,
            ['plugin' => $this->getName(), 'controller' => 'Posts', 'action' => 'viewPost'],
            [
                '_name' => 'Post', // make accessible as 'Blog:Post'
                'pass'=> ['post_id', 'postname'], // pass only these identifiers
                /*
                 * Wordpress allows nested categories, so category path may
                 * contain slashes. Allowing `/` in regular expression makes it
                 * greedy, so it captures `paths/with/slashes` as one $category.
                 * If this is not done, `paths/with/slashes` will be split into
                 * multiple variables, and the route will not be matched.
                 */
                'category' => '[a-zA-Z0-9-_/]+', // allow slashes in $category
            ]
        );
    }
);
