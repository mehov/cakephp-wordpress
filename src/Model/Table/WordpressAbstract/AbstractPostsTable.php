<?php

namespace CakePHPWordpress\Model\Table\WordpressAbstract;

abstract class AbstractPostsTable extends \CakePHPWordpress\Model\Table\PluginTable
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
