<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Entity\Wordpress6;

/**
 * TermRelationship Entity
 *
 * @property int $object_id
 * @property int $term_taxonomy_id
 * @property int $term_order
 */
class TermRelationship extends \CakePHPWordpress\Model\Entity\WordpressAbstract\AbstractTermRelationship
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
        'term_order' => true,
    ];
}
