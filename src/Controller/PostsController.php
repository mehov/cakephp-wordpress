<?php

namespace CakePHPWordpress\Controller;

class PostsController extends AppController
{

    public function index()
    {
        $blog = new \CakePHPWordpress\Connector();
        $query = $blog->Posts->findPublishedPosts();
        $this->set([
            'posts' => $query->all(),
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

}