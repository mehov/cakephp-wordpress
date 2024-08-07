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

    /**
     * Custom finder method for wp_posts that are blog posts
     *
     * @param \Cake\ORM\Query\SelectQuery $query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findPosts($query)
    {
        $alias = $query->getRepository()->getAlias();
        return $query
            ->where([$alias.'.post_type' => 'post'])
            ->contain(['Categories', 'PostTags'])
            ->order($alias.'.post_date DESC')
        ;
    }

    /**
     * Custom finder method for wp_posts that are pages
     *
     * @param \Cake\ORM\Query\SelectQuery $query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findPages($query)
    {
        $alias = $query->getRepository()->getAlias();
        return $query
            ->where([$alias.'.post_type' => 'page'])
            /*
             * From the Wordpress Help:
             * "Pages are usually ordered alphabetically, but you can choose
             * your own order by entering a number (1 for first, etc.)"
             *
             * First, order by menu_order, and then alphabetically
             */
            ->order([$alias.'.menu_order ASC', $alias.'.post_title ASC'])
        ;
    }

    /**
     * Custom finder method for all published wp_posts (regardless of post_type)
     *
     * @param \Cake\ORM\Query\SelectQuery $query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findPublished($query)
    {
        $alias = $query->getRepository()->getAlias();
        return $query->where([$alias.'.post_status' => 'publish']);
    }

}
