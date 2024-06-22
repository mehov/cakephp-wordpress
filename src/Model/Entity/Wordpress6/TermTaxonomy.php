<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Entity\Wordpress6;

/**
 * TermTaxonomy Entity
 *
 * @property int $term_taxonomy_id
 * @property int $term_id
 * @property string $taxonomy
 * @property string $description
 * @property int $parent
 * @property int $count
 */
class TermTaxonomy extends \CakePHPWordpress\Model\Entity\WordpressAbstract\AbstractTermTaxonomy
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
        'term_id' => true,
        'taxonomy' => true,
        'description' => true,
        'parent' => true,
        'count' => true,
    ];
}
