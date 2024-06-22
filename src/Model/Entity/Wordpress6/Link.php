<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Entity\Wordpress6;

/**
 * Link Entity
 *
 * @property int $link_id
 * @property string $link_url
 * @property string $link_name
 * @property string $link_image
 * @property string $link_target
 * @property string $link_description
 * @property string $link_visible
 * @property int $link_owner
 * @property int $link_rating
 * @property \Cake\I18n\DateTime $link_updated
 * @property string $link_rel
 * @property string $link_notes
 * @property string $link_rss
 */
class Link extends \CakePHPWordpress\Model\Entity\WordpressAbstract\AbstractLink
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
        'link_url' => true,
        'link_name' => true,
        'link_image' => true,
        'link_target' => true,
        'link_description' => true,
        'link_visible' => true,
        'link_owner' => true,
        'link_rating' => true,
        'link_updated' => true,
        'link_rel' => true,
        'link_notes' => true,
        'link_rss' => true,
    ];
}
