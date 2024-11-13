<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Table\Wordpress6;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Links Model
 *
 * @method \CakePHPWordpress\Model\Entity\Link newEmptyEntity()
 * @method \CakePHPWordpress\Model\Entity\Link newEntity(array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Link> newEntities(array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Link get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \CakePHPWordpress\Model\Entity\Link findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Link patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Link> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Link|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Link saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Link>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Link>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Link>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Link> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Link>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Link>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Link>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Link> deleteManyOrFail(iterable $entities, array $options = [])
 */
class LinksTable extends \CakePHPWordpress\Model\Table\WordpressAbstract\AbstractLinksTable
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('links');
        $this->setDisplayField('link_url');
        $this->setPrimaryKey('link_id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('link_url')
            ->maxLength('link_url', 255)
            ->notEmptyString('link_url');

        $validator
            ->scalar('link_name')
            ->maxLength('link_name', 255)
            ->notEmptyString('link_name');

        $validator
            ->scalar('link_image')
            ->maxLength('link_image', 255)
            ->notEmptyFile('link_image');

        $validator
            ->scalar('link_target')
            ->maxLength('link_target', 25)
            ->notEmptyString('link_target');

        $validator
            ->scalar('link_description')
            ->maxLength('link_description', 255)
            ->notEmptyString('link_description');

        $validator
            ->scalar('link_visible')
            ->maxLength('link_visible', 20)
            ->notEmptyString('link_visible');

        $validator
            ->notEmptyString('link_owner');

        $validator
            ->integer('link_rating')
            ->notEmptyString('link_rating');

        $validator
            ->dateTime('link_updated')
            ->notEmptyDateTime('link_updated');

        $validator
            ->scalar('link_rel')
            ->maxLength('link_rel', 255)
            ->notEmptyString('link_rel');

        $validator
            ->scalar('link_notes')
            ->maxLength('link_notes', 16777215)
            ->requirePresence('link_notes', 'create')
            ->notEmptyString('link_notes');

        $validator
            ->scalar('link_rss')
            ->maxLength('link_rss', 255)
            ->notEmptyString('link_rss');

        return $validator;
    }
}
