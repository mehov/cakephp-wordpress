<?php

namespace CakePHPWordpress\Routing\Route;

class PageRoute extends \Cake\Routing\Route\Route
{

    /**
     * Checks if $url points to a published page in wp_posts.
     *
     * If page exists, complete the page route with found page ID.
     * If page doesn't exist, return null so Router tries other routes for $url.
     *
     * @param string $url The URL to attempt to parse
     * @param string $method The HTTP method of the request being parsed
     * @return array|null An array of request parameters, or `null` if not found
     */
    public function parse($url, $method): ?array
    {
        // Re-use built-in parser from parent class
        $parsed = parent::parse($url, $method);
        // Pass contains pieces of $url split by /; this is shorthand to them
        $path = $parsed['pass'];
        // Start the blog connector
        $blog = new \CakePHPWordpress\Connector();
        // Fetch table class for wp_posts where Wordpress stores pages
        $postsTable = $blog->Posts;
        $postsAlias = $postsTable->getAlias();
        // Start building query to look requested page up
        $query = $postsTable->find('pages')->find('published');
        // Take last piece in page path, this is post_name of our final page
        $the_page = array_pop($path);
        $query->where([$postsAlias.'.post_name' => $the_page]);
        /*
         * If requested page path is multiple level, e.g. /company/about/contact
         * then we have to make sure all parent pages exist too. Below loop goes
         * through remaining items in $path and adds respective joins to query.
         */
        // On each loop iteration we need to know previous alias to join parent
        $previousAlias = $postsAlias;
        // Loop through remaining path pieces after array_pop()
        foreach (array_reverse($path) as $key => $page) {
            $alias = $postsAlias.($key+1); // +1 so that we don't start from 0
            // Join self to look up each parent page
            $query->join(array(
                'type' => 'LEFT',
                'alias' => $alias,
                'table' => $postsTable->getTable(),
                'conditions' => array(
                    $alias.'.ID = '.$previousAlias.'.post_parent',
                    $alias.'.post_type' => 'page',
                    $alias.'.post_status' => 'publish',
                ),
            ));
            $query->where([$alias.'.post_name' => $page]);
            $previousAlias = $alias; // next iteration will refer this as parent
        }
        $results = $query->all();
        if (!$results || $results->count() === 0) {
            // Page was not found. Force Router to try other routes in this app
            return null;
        }
        // Get the page we found
        $page = $results->first();
        // Use ID of page we found as `pass` and complete the route
        $parsed['pass'] = [$page->get('ID')];
        return $parsed;
    }

}