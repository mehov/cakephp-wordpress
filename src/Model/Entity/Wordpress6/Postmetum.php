<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Entity\Wordpress6;

/**
 * Postmetum Entity
 *
 * @property int $meta_id
 * @property int $post_id
 * @property string|null $meta_key
 * @property string|null $meta_value
 */
class Postmetum extends \CakePHPWordpress\Model\Entity\WordpressAbstract\AbstractPostmetum
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
        'post_id' => true,
        'meta_key' => true,
        'meta_value' => true,
    ];
}
