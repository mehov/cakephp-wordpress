<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Entity\Wordpress6;

/**
 * Term Entity
 *
 * @property int $term_id
 * @property string $name
 * @property string $slug
 * @property int $term_group
 */
class Term extends \CakePHPWordpress\Model\Entity\WordpressAbstract\AbstractTerm
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
        'name' => true,
        'slug' => true,
        'term_group' => true,
    ];
}
