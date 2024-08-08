<?php

namespace CakePHPWordpress\Model\Entity\WordpressAbstract;

abstract class AbstractPost extends \CakePHPWordpress\Model\Entity\PluginEntity
{

    /**
     * Recursively traverses a nested item structure to find all parent items for a given item.
     *
     * ("nested item structure" = categories and pages because they can have parents)
     *
     * Using categories as an example:
     *
     * This function searches through a nested array of categories to find a specific category by its ID.
     * Once the category is found, it collects the names of all its parent categories, including the category itself,
     * and stores them in a flat array in the order from root to the given category.
     *
     * $idGetter and $slugGetter are anonymous functions because IDs and slugs
     * in pages and categories are stored as different properties
     *
     * @param int $item The item for which to find the parent items.
     * @param array $items The initial call should pass the entire nested array of items.
     *                     Each item object should have 'id', 'term' (with 'name'), and optionally 'children'.
     *                     As the function calls itself recursively, it passes the 'children' property of each item,
     *                     which narrows down the search to the subtree rooted at that item.
     * @param callable(\CakePHPWordpress\Model\Entity\PluginEntity): string $idGetter receives entity and returns its ID value
     * @param callable(\CakePHPWordpress\Model\Entity\PluginEntity): string $slugGetter receives entity and returns its slug value
     * @param array &$result An array passed by reference that will be populated with the names of the parent items, including the current item.
     *
     * @return bool Returns true if the item is found and its parent items are added to the result array, false otherwise.
     */
    private function _flattenTreeUntilRoot($currentItem, $items, $idGetter, $slugGetter, &$result = [])
    {
        /**
         * Traverse the nested item structure.
         *
         * We start with the entire nested array of items. The function will be called recursively,
         * each time with a narrower scope (the children of the current item).
         */
        foreach ($items as $item) {
            // Check if the current item is the one we are looking for
            if ($idGetter($item) == $idGetter($currentItem)) {
                /**
                 * Found the item, prepend it to the result.
                 *
                 * When we find the item with the specified ID, we add its name to the result array.
                 * We use array_unshift to add it to the beginning of the array.
                 * This way, the root item will be added first, and the current item will be added last.
                 */
                array_unshift($result, $slugGetter($item));
                return true; // Start returning true to indicate the item was found
            }
            // If the current item has children, recursively search in the children
            if (!empty($item->children)) {
                /**
                 * We call the same function on the children of the current item. If the function
                 * returns true, it means the target item was found somewhere in this subtree.
                 */
                if ($this->_flattenTreeUntilRoot($currentItem, $item->children, $idGetter, $slugGetter, $result)) {
                    /**
                     * If the target item is found in the children, add this item to the result.
                     *
                     * Since the child call returned true, we know that the current item is an ancestor
                     * of the target item. We add its name to the beginning of the result array.
                     */
                    array_unshift($result, $slugGetter($item));
                    return true; // Continue returning true to propagate the success up the call stack
                }
            }
        }
        // If the item is not found in this subtree, return false
        return false;
    }

    protected function _getUrl()
    {
        switch ($this->post_type) {
            case 'post':
                return $this->_getPostUrl();
                break;
            case 'page':
                return $this->_getPageUrl();
                break;
        }
        return null;
    }

    private function _getPostUrl()
    {
        $permalink_structure = \Cake\Core\Configure::read(
            'CakePHPWordpress.Options.permalink_structure'
        );
        // Proceed only if permalink needs to have a category
        if (strpos($permalink_structure, '%category%') !== false) {
            // Try to read categories preloaded in plugin config/bootstrap.php
            $categories = \Cake\Core\Configure::read('CakePHPWordpress.Categories');
            if (empty($categories) || !is_array($categories)) {
                throw new \Exception('Blog categories not preloaded');
            }
            // Make sure the post we're in was loaded with Categories contained
            if (empty($this->categories) || !is_array($this->categories)) {
                throw new \Exception('Post categories not fetched');
            }
            // Get the first available category
            $category = reset($this->categories);
            $tree = [];
            // Build category tree for the current post
            $this->_flattenTreeUntilRoot(
                $category, // the category of the post we're building URL for
                $categories, // all of blog categories, preloaded in bootstrap
                function($item) {return $item->term_taxonomy_id;}, // ID getter
                function($item) {return $item->term->slug;}, // slug getter
                $tree
            );
            // Above function returns flat array, from root to current level
            $category_path = implode('/', $tree);
        } else {
            $category_path = null; // placeholder for str_replace() to work
        }
        $placeholders = [
            '%year%',
            '%monthnum%',
            '%day%',
            '%hour%',
            '%minute%',
            '%second%',
            '%post_id%',
            '%postname%',
            '%category%',
            '%author%',
        ];
        $values = [
            $this->post_date->format('Y'),
            $this->post_date->format('m'),
            $this->post_date->format('d'),
            $this->post_date->format('H'),
            $this->post_date->format('i'),
            $this->post_date->format('s'),
            $this->ID,
            $this->post_name,
            $category_path,
            $this->post_author,
        ];
        $url = str_replace($placeholders, $values, $permalink_structure);
        return \Cake\Routing\Router::url($url, true);
    }

    private function _getPageUrl()
    {
        /*
         * Expecting all pages to be preloaded and threaded, so that we do not
         * have to query page hierarchy each time we need to build page URL.
         *
         * See PLUGIN/config/bootstrap.php
         */
        $pages = \Cake\Core\Configure::read('CakePHPWordpress.Pages');
        if (empty($pages) || !is_array($pages)) {
            throw new \Exception('Pages not preloaded');
        }
        // Full path from site root up to this page will be stored here
        $tree = [];
        // Use all preloaded pages to find full path from root up to this page
        $this->_flattenTreeUntilRoot(
            $this, // this page we're building URL for
            $pages, // all published blog pages, preloaded in bootstrap
            function($item) {return $item->ID;}, // ID getter
            function($item) {return $item->post_name;}, // slug getter
            $tree
        );
        // Above function returns flat array, from root to current level
        return \Cake\Routing\Router::url(implode('/', $tree), true);
    }

}
