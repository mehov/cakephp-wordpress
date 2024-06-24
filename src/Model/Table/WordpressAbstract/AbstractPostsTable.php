<?php

namespace CakePHPWordpress\Model\Table\WordpressAbstract;

use Cake\ORM\Table;

abstract class AbstractPostsTable extends Table
{

    public function findPosts($type = 'all', $options = [])
    {
        $defaults = [
            'conditions' => [
                'post_type' => 'post',
            ],
            'order' => 'post_date DESC',
            'limit' => 5,
        ];
        $options += $defaults;
        return $this->find($type, $options);
    }

    public function findPublishedPosts($type = 'all', $options = [])
    {
        $options['conditions']['post_type'] = 'post';
        $options['conditions']['post_status'] = 'publish';
        return $this->findPosts($type, $options);
    }

}
