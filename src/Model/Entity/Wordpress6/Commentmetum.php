<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Entity\Wordpress6;

/**
 * Commentmetum Entity
 *
 * @property int $meta_id
 * @property int $comment_id
 * @property string|null $meta_key
 * @property string|null $meta_value
 */
class Commentmetum extends \CakePHPWordpress\Model\Entity\WordpressAbstract\AbstractCommentmetum
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
        'comment_id' => true,
        'meta_key' => true,
        'meta_value' => true,
    ];
}
