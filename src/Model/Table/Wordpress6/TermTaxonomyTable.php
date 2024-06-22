<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Table\Wordpress6;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * TermTaxonomy Model
 *
 * @method \CakePHPWordpress\Model\Entity\TermTaxonomy newEmptyEntity()
 * @method \CakePHPWordpress\Model\Entity\TermTaxonomy newEntity(array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\TermTaxonomy> newEntities(array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\TermTaxonomy get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \CakePHPWordpress\Model\Entity\TermTaxonomy findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\TermTaxonomy patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\TermTaxonomy> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\TermTaxonomy|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\TermTaxonomy saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\TermTaxonomy>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\TermTaxonomy>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\TermTaxonomy>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\TermTaxonomy> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\TermTaxonomy>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\TermTaxonomy>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\TermTaxonomy>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\TermTaxonomy> deleteManyOrFail(iterable $entities, array $options = [])
 */
class TermTaxonomyTable extends \CakePHPWordpress\Model\Table\WordpressAbstract\AbstractTermTaxonomyTable
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

        $this->setTable('wp_term_taxonomy');
        $this->setDisplayField('taxonomy');
        $this->setPrimaryKey('term_taxonomy_id');
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
            ->notEmptyString('term_id');

        $validator
            ->scalar('taxonomy')
            ->maxLength('taxonomy', 32)
            ->notEmptyString('taxonomy');

        $validator
            ->scalar('description')
            ->maxLength('description', 4294967295)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->notEmptyString('parent');

        $validator
            ->notEmptyString('count');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['term_id', 'taxonomy']), ['errorField' => 'term_id']);

        return $rules;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName(): string
    {
        return 'Wordpress6-clean';
    }
}
