<?php

namespace CakePHPWordpress\Model\Table\WordpressAbstract;

abstract class AbstractPostsTable extends \CakePHPWordpress\Model\Table\PluginTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->belongsToMany('Categories', [
            'className' => 'CakePHPWordpress.Categories',
            'through' => 'CakePHPWordpress.TermRelationships',
            'foreignKey' => 'object_id',
            'targetForeignKey' => 'term_taxonomy_id',
        ]);
        $this->belongsToMany('PostTags', [
            'className' => 'CakePHPWordpress.PostTags',
            'through' => 'CakePHPWordpress.TermRelationships',
            'foreignKey' => 'object_id',
            'targetForeignKey' => 'term_taxonomy_id',
        ]);
    }

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
        return $this->find($type, $options)->contain(['Categories', 'PostTags']);
    }

    public function findPublishedPosts($type = 'all', $options = [])
    {
        $options['conditions']['post_type'] = 'post';
        $options['conditions']['post_status'] = 'publish';
        return $this->findPosts($type, $options);
    }

}
