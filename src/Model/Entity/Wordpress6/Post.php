<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Entity\Wordpress6;

/**
 * Post Entity
 *
 * @property int $ID
 * @property int $post_author
 * @property \Cake\I18n\DateTime $post_date
 * @property \Cake\I18n\DateTime $post_date_gmt
 * @property string $post_content
 * @property string $post_title
 * @property string $post_excerpt
 * @property string $post_status
 * @property string $comment_status
 * @property string $ping_status
 * @property string $post_password
 * @property string $post_name
 * @property string $to_ping
 * @property string $pinged
 * @property \Cake\I18n\DateTime $post_modified
 * @property \Cake\I18n\DateTime $post_modified_gmt
 * @property string $post_content_filtered
 * @property int $post_parent
 * @property string $guid
 * @property int $menu_order
 * @property string $post_type
 * @property string $post_mime_type
 * @property int $comment_count
 */
class Post extends \CakePHPWordpress\Model\Entity\WordpressAbstract\AbstractPost
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'post_author' => true,
        'post_date' => true,
        'post_date_gmt' => true,
        'post_content' => true,
        'post_title' => true,
        'post_excerpt' => true,
        'post_status' => true,
        'comment_status' => true,
        'ping_status' => true,
        'post_password' => true,
        'post_name' => true,
        'to_ping' => true,
        'pinged' => true,
        'post_modified' => true,
        'post_modified_gmt' => true,
        'post_content_filtered' => true,
        'post_parent' => true,
        'guid' => true,
        'menu_order' => true,
        'post_type' => true,
        'post_mime_type' => true,
        'comment_count' => true,
    ];
}
