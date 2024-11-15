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

    /**
     * When looking up a page by URL/path, we must make sure the whole hierarchy
     * exists. This finder splits the path, makes sure the page on every level
     * exists and (if any) page mentioned to the left is actually a parent to it
     *
     * Usage example:
     * ```
     * $path = '/about-us/our-story';
     * $postsTable->find('pages')->find('published')->find('byPath', $path);
     * ```
     *
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param string $path e.g. /about/story or http://example.com/about/story
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByPath($query, $path)
    {
        // Make sure we get the path only
        $path = parse_url($path, PHP_URL_PATH);
        // Trim slashes to prevent empty pieces, then split into pieces
        $pieces = explode('/', trim($path, '/'));
        // Take last piece in page path, this is post_name of our final page
        $the_page = array_pop($pieces);
        $query->where([$this->getAlias().'.post_name' => $the_page]);
        /*
         * If requested page path is multiple level, e.g. /company/about/contact
         * then we have to make sure all parent pages exist too. Below loop goes
         * through remaining items in $path and adds respective joins to query.
         */
        // On each loop iteration we need to know previous alias to join parent
        $previousAlias = $this->getAlias();
        // Loop through remaining path pieces after array_pop()
        foreach (array_reverse($pieces) as $key => $page) {
            $alias = $this->getAlias().($key+1); // +1 so we don't start from 0
            // Join self to look up each parent page
            $query->join(array(
                'type' => 'LEFT',
                'alias' => $alias,
                'table' => $this->getTable(),
                'conditions' => array(
                    $alias.'.ID = '.$previousAlias.'.post_parent',
                    $alias.'.post_type' => 'page',
                    $alias.'.post_status' => 'publish',
                ),
            ));
            $query->where([$alias.'.post_name' => $page]);
            $previousAlias = $alias; // next iteration will refer this as parent
        }
        return $query;
    }

}
