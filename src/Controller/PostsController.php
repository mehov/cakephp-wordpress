<?php

namespace CakePHPWordpress\Controller;

use \Cake\Utility\Inflector;

class PostsController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->addHelper('Paginator', [
            'templates' => $this->getPlugin().'.paginator-templates'
        ]);
    }

    public function index()
    {
        $blog = new \CakePHPWordpress\Connector();
        $query = $blog->Posts->findPublishedPosts();
        $this->set([
            'posts' => $this->paginate($query),
        ]);
    }

    public function view($identifier)
    {
        if (is_numeric($identifier)) {
            $conditions['ID'] = $identifier;
        } else {
            $conditions['post_name'] = $identifier;
        }
        $blog = new \CakePHPWordpress\Connector();
        $query = $blog->Posts->findPublishedPosts()->where($conditions);
        $this->set([
            'post' => $query->first(),
        ]);
    }

    /**
     * Shared destination for all Term Taxonomy routes from config/routes.php
     * Finds posts for given term taxonomy (category or post_tag).
     * TODO: use this as home page for that specific term.
     *
     * @param string $route_name Blog:category|Blog:post_tag
     * @param string $slug from `terms` table
     */
    public function routedTermTaxonomy($route_name, $slug)
    {
        // Received route name is "blog prefix":"term taxonomy"; split it
        $route_name_parts = explode(':', $route_name);
        // Convert underscored WP term taxonomy to our table class name here
        $table = Inflector::pluralize(Inflector::camelize($route_name_parts[1]));
        // Get the blog connector
        $blog = new \CakePHPWordpress\Connector();
        // Query published posts that belong to requested term
        $query = $blog->Posts->findPublishedPosts()
            ->matching($table.'.Terms', function ($q) use ($slug) {
                return $q->where(['Terms.slug' => $slug]);
            });
        // Reuse index template
        $this->viewBuilder()->setTemplate('index');
        $this->set([
            'posts' => $this->paginate($query),
        ]);
    }
}