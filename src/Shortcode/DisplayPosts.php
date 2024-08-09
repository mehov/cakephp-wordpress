<?php

namespace CakePHPWordpress\Shortcode;

/**
 * Handles [display-posts] shortcode
 * See: https://wordpress.com/support/display-posts-shortcode/
 *
 * @package CakePHPWordpress\Shortcode
 */
class DisplayPosts extends AbstractShortcode
{

    /**
     * Default template used for embedding a post. If you need to change it,
     * create App/Shortcode/DisplayPosts.php with the following content:
     *
     *     namespace App\Shortcode;
     *
     *     class DisplayPosts extends \CakePHPWordpress\Shortcode\DisplayPosts
     *     {
     *         protected function embeddedPostTemplate()
     *         {
     *             return 'your preferred template';
     *         }
     *     }
     *
     * Check render() below for a list of placeholders that can be used.
     * @return string
     */
    protected function embeddedPostTemplate()
    {
        return '<div><h2 id="%post_name%">%post_title%</h2><div>%post_content%</div></div>';
    }

    /**
     * Takes attributes passed with shortcode and returns find() conditions
     *
     * @param $attributes raw attributes parsed out of the shortcode
     * @return array conditions that can be used to find() posts
     */
    private static function conditionsFromAttributes($attributes)
    {
        // Map incoming attribute keys to whitelisted conditions keys
        $whitelisted = [
            'id' => 'ID',
            'post_type' => 'post_type',
        ];
        // Filter out attributes by keys that are not whitelisted
        $filtered = array_intersect_key($attributes, $whitelisted);
        // Replace attribute keys with their whitelisted conditions counterparts
        return array_combine(
            array_map(fn($key) => $whitelisted[$key], array_keys($filtered)),
            $filtered
        );
    }

    /**
     * See \CakePHPWordpress\Shortcode\AbstractShortcode::render()
     */
    public function render($attributes, $content, $tag) {
        $blog = new \CakePHPWordpress\Connector();
        $conditions = self::conditionsFromAttributes($attributes);
        $query = $blog->Posts->find('published')->where($conditions);
        $posts = $query->all();
        if ($posts->count() === 0) {
            return null;
        }
        $placeholders = array(
            '%ID%',
            '%post_name%',
            '%post_title%',
            '%post_content%',
        );

        $return = '';
        foreach ($posts as $post) {
            $replacements = array(
                $post->ID,
                $post->post_name,
                $post->post_title,
                $post->the_content(),
            );
            $return.= str_replace($placeholders, $replacements, $this->embeddedPostTemplate());
        }
        return $return;
    }

}