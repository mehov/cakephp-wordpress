<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Entity\Wordpress6;

/**
 * Option Entity
 *
 * @property int $option_id
 * @property string $option_name
 * @property string $option_value
 * @property string $autoload
 */
class Option extends \CakePHPWordpress\Model\Entity\WordpressAbstract\AbstractOption
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
        'option_name' => true,
        'option_value' => true,
        'autoload' => true,
    ];
}
