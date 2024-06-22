<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Entity\Wordpress6;

/**
 * Comment Entity
 *
 * @property int $comment_ID
 * @property int $comment_post_ID
 * @property string $comment_author
 * @property string $comment_author_email
 * @property string $comment_author_url
 * @property string $comment_author_IP
 * @property \Cake\I18n\DateTime $comment_date
 * @property \Cake\I18n\DateTime $comment_date_gmt
 * @property string $comment_content
 * @property int $comment_karma
 * @property string $comment_approved
 * @property string $comment_agent
 * @property string $comment_type
 * @property int $comment_parent
 * @property int $user_id
 */
class Comment extends \CakePHPWordpress\Model\Entity\WordpressAbstract\AbstractComment
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
        'comment_post_ID' => true,
        'comment_author' => true,
        'comment_author_email' => true,
        'comment_author_url' => true,
        'comment_author_IP' => true,
        'comment_date' => true,
        'comment_date_gmt' => true,
        'comment_content' => true,
        'comment_karma' => true,
        'comment_approved' => true,
        'comment_agent' => true,
        'comment_type' => true,
        'comment_parent' => true,
        'user_id' => true,
    ];
}
