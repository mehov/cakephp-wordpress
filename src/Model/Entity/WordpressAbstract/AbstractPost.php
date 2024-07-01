<?php

namespace CakePHPWordpress\Model\Entity\WordpressAbstract;

abstract class AbstractPost extends \CakePHPWordpress\Model\Entity\PluginEntity
{

    /**
     * Recursively traverses a nested category structure to find all parent categories for a given category ID.
     *
     * This function searches through a nested array of categories to find a specific category by its ID.
     * Once the category is found, it collects the names of all its parent categories, including the category itself,
     * and stores them in a flat array in the order from root to the given category.
     *
     * @param array $categories The initial call should pass the entire nested array of categories.
     *                          Each category object should have 'id', 'term' (with 'name'), and optionally 'children'.
     *                          As the function calls itself recursively, it passes the 'children' property of each category,
     *                          which narrows down the search to the subtree rooted at that category.
     * @param int $categoryId The ID of the category for which to find the parent categories.
     * @param array &$result An array passed by reference that will be populated with the names of the parent categories, including the current category.
     *
     * @return bool Returns true if the category is found and its parent categories are added to the result array, false otherwise.
     */
    private function getParentCategoryTree($categories, $categoryId, &$result = [])
    {
        /**
         * Traverse the nested category structure.
         *
         * We start with the entire nested array of categories. The function will be called recursively,
         * each time with a narrower scope (the children of the current category).
         */
        foreach ($categories as $category) {
            // Check if the current category is the one we are looking for
            if ($category->term_taxonomy_id == $categoryId) {
                /**
                 * Found the category, prepend it to the result.
                 *
                 * When we find the category with the specified ID, we add its name to the result array.
                 * We use array_unshift to add it to the beginning of the array.
                 * This way, the root category will be added first, and the current category will be added last.
                 */
                array_unshift($result, $category->term->slug);
                return true; // Start returning true to indicate the category was found
            }
            // If the current category has children, recursively search in the children
            if (!empty($category->children)) {
                /**
                 * We call the same function on the children of the current category. If the function
                 * returns true, it means the target category was found somewhere in this subtree.
                 */
                if ($this->getParentCategoryTree($category->children, $categoryId, $result)) {
                    /**
                     * If the target category is found in the children, add this category to the result.
                     *
                     * Since the child call returned true, we know that the current category is an ancestor
                     * of the target category. We add its name to the beginning of the result array.
                     */
                    array_unshift($result, $category->term->slug);
                    return true; // Continue returning true to propagate the success up the call stack
                }
            }
        }
        // If the category is not found in this subtree, return false
        return false;
    }

    public function _getUrl()
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
            $this->getParentCategoryTree($categories, $category->term_taxonomy_id, $tree);
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
        return \Cake\Routing\Router::url($url);
    }

}
