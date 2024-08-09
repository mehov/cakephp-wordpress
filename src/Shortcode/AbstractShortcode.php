<?php

namespace CakePHPWordpress\Shortcode;

use \Cake\Utility\Inflector;

abstract class AbstractShortcode
{

    /**
     * This class is supposed to be extended by shortcode handlers. Each handler
     * supports respective shortcode, e.g. Audio handler for [audio] shortcode.
     *
     * When shortcode is found, it gets parsed and its handler initialised. Then
     * render() is called on the handler class. It receives shortcode info and
     * is expected to return rendered shortcode content to be inserted into post
     * or page content.
     *
     * Each child handler class must implement a render() method.
     *
     * This is similar to $callback function from add_shortcode() in Wordpress
     *
     * More info: https://developer.wordpress.org/reference/functions/add_shortcode/#parameters
     *
     * @param array $attributes shortcode attributes
     * @param string|null $content if shortcode has closing tag
     * @param string $tag name of shortcode tag
     * @return string
     */
    abstract public function render($attributes, $content, $tag);

    /**
     * Returns tag names of shortcodes for which we have handlers
     *
     * @return array
     */
    public static function getRegisteredShortcodes()
    {
        // Registered shortcodes that we found will be here
        $shortcodes = [];
        // Looking for handlers inside current folder as well as APP/Shortcode
        $pattern = '{'.__DIR__.'/*.php,'.APP.'/Shortcode/*.php}';
        foreach (glob($pattern, GLOB_BRACE) as $file) {
            $basename = basename($file, '.php');
            // Skip the abstract class
            if (stripos($basename, 'abstract') === 0) {
                continue;
            }
            // Get the dashed shortcode tag name out of camelcased class name
            $shortcodes[] = Inflector::dasherize($basename);
        }
        return $shortcodes;
    }

    /**
     * Takes post content (incl. pages) and expands valid shortcodes, if found
     *
     * @param string $content entry content from wp_posts (so both posts and pages)
     * @return string $content with shortcodes processed by respective handlers
     */
    public static function expandContent($content)
    {
        $pattern = self::get_shortcode_regex();
        $content = preg_replace_callback( "/$pattern/", 'self::do_shortcode_tag', $content );
        return $content;
    }

    /*
     * Below is shortcode handling code copied from Wordpress, with adjustments
     */

    /**
     * Copied from https://github.com/WordPress/WordPress/blob/ddab80be2c0623cc8971a75180de40343f839f71/wp-includes/shortcodes.php#L324
     *
     * Retrieves the shortcode regular expression for searching.
     *
     * The regular expression combines the shortcode tags in the regular expression
     * in a regex class.
     *
     * The regular expression contains 6 different sub matches to help with parsing.
     *
     * 1 - An extra [ to allow for escaping shortcodes with double [[]]
     * 2 - The shortcode name
     * 3 - The shortcode argument list
     * 4 - The self closing /
     * 5 - The content of a shortcode when it wraps some content.
     * 6 - An extra ] to allow for escaping shortcodes with double [[]]
     * @return string The shortcode search regular expression
     */
    public static function get_shortcode_regex() {
        $tagnames = self::getRegisteredShortcodes();
        $tagregexp = implode( '|', array_map( 'preg_quote', $tagnames ) );

        return '\\['                             // Opening bracket.
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]].
            . "($tagregexp)"                     // 2: Shortcode name.
            . '(?![\\w-])'                       // Not followed by word character or hyphen.
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag.
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash.
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket.
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash.
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag...
            .     '\\]'                          // ...and closing bracket.
            . '|'
            .     '\\]'                          // Closing bracket.
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags.
            .             '[^\\[]*+'             // Not an opening bracket.
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag.
            .                 '[^\\[]*+'         // Not an opening bracket.
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag.
            .     ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing bracket for escaping shortcodes: [[tag]].
    }

    /**
     * Copied from https://core.trac.wordpress.org/browser/tags/3.5.1/wp-includes/shortcodes.php#L223
     *
     * Regular Expression callable for do_shortcode() for calling shortcode hook.
     * @see get_shortcode_regex for details of the match array contents.
     *
     * @param array $m Regular expression match array
     * @return mixed False on failure.
     */
    public static function do_shortcode_tag($m)
    {
        // allow [[foo]] syntax for escaping a tag
        if ( $m[1] == '[' && $m[6] == ']' ) {
            return substr($m[0], 1, -1);
        }
        $tag = $m[2];
        $attr = self::shortcode_parse_atts( html_entity_decode($m[3]) );
        /*
         * EDIT by CakePHPWordpress.
         *
         * Tags can either have enclosing, or be self-closing.
         * Examples:
         * - self-closing: [gallery ids="1,2,3"]
         * - with enclosing tag: [audio src="file.mp3"][/audio]
         *
         * If $m[5] is set, tag has enclosing, and may have content inside.
         */
        $content = isset($m[5]) ? $m[5] : null;
        // Build fully qualified handler class name and look for the class
        $className = Inflector::camelize(Inflector::underscore($tag));
        $classNameApp = 'App\\Shortcode\\' . $className;
        $classNamePlugin = __NAMESPACE__ . '\\' . $className;
        // First, check if a shortcode handler override exists on the app level
        if (class_exists($classNameApp)) {
            $class = new $classNameApp();
        // Then check if a shortcode handler exists on the plugin level
        } elseif (class_exists($classNamePlugin)) {
            $class = new $classNamePlugin();
        } else {
        // If handler class exists neither in app nor in plugin, return early
            return null;
        }
        // Call render() on shortcode handler
        return $class->render($attr, $content, $tag);
    }

    /**
     * Retrieves all attributes from the shortcodes tag.
     *
     * The attributes list has the attribute name as the key and the value of the
     * attribute as the value in the key/value pair. This allows for easier
     * retrieval of the attributes, since all attributes have to be known.
     *
     * @param string $text Shortcode arguments list.
     * @return array Array of attribute values keyed by attribute name.
     *               Returns empty array if there are no attributes
     *               or if the original arguments string cannot be parsed.
     */
    public static function shortcode_parse_atts($text) {
        $atts    = array();
        $pattern = '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|\'([^\']*)\'(?:\s|$)|(\S+)(?:\s|$)/';
        $text    = preg_replace( "/[\x{00a0}\x{200b}]+/u", ' ', $text );
        if ( preg_match_all( $pattern, $text, $match, PREG_SET_ORDER ) ) {
            foreach ( $match as $m ) {
                if ( ! empty( $m[1] ) ) {
                    $atts[ strtolower( $m[1] ) ] = stripcslashes( $m[2] );
                } elseif ( ! empty( $m[3] ) ) {
                    $atts[ strtolower( $m[3] ) ] = stripcslashes( $m[4] );
                } elseif ( ! empty( $m[5] ) ) {
                    $atts[ strtolower( $m[5] ) ] = stripcslashes( $m[6] );
                } elseif ( isset( $m[7] ) && strlen( $m[7] ) ) {
                    $atts[] = stripcslashes( $m[7] );
                } elseif ( isset( $m[8] ) && strlen( $m[8] ) ) {
                    $atts[] = stripcslashes( $m[8] );
                } elseif ( isset( $m[9] ) ) {
                    $atts[] = stripcslashes( $m[9] );
                }
            }

            // Reject any unclosed HTML elements.
            foreach ( $atts as &$value ) {
                if ( str_contains( $value, '<' ) ) {
                    if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
                        $value = '';
                    }
                }
            }
        }

        return $atts;
    }

}