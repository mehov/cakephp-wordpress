<?php

namespace CakePHPWordpress\Routing\Route;

class PageRoute extends \Cake\Routing\Route\Route
{

    /**
     * Checks if $path points to a published page in wp_posts.
     *
     * If page exists, complete the page route with found page ID.
     * If page doesn't exist, return null so Router tries other routes for $path
     *
     * @param string $path The URL path to attempt to parse
     * @param string $method The HTTP method of the request being parsed
     * @return array|null An array of request parameters, or `null` if not found
     */
    public function parse($path, $method): ?array
    {
        // Start the blog connector
        $blog = new \CakePHPWordpress\Connector();
        // Fetch table class for wp_posts where Wordpress stores pages
        $postsTable = $blog->Posts;
        // Build query to look requested page up
        $query = $postsTable
            ->find('pages')
            ->find('published')
            ->find('byPath', $path)
            ;
        $results = $query->all();
        if (!$results || $results->count() === 0) {
            // Page was not found. Force Router to try other routes in this app
            return null;
        }
        // Get the page we found
        $page = $results->first();
        // Re-use built-in parser from parent class
        $parsed = parent::parse($path, $method);
        // Use ID of page we found as `pass` and complete the route
        $parsed['pass'] = [$page->get('ID')];
        return $parsed;
    }

}