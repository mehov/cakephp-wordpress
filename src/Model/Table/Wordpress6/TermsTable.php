<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Table\Wordpress6;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Terms Model
 *
 * @method \CakePHPWordpress\Model\Entity\Term newEmptyEntity()
 * @method \CakePHPWordpress\Model\Entity\Term newEntity(array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Term> newEntities(array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Term get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \CakePHPWordpress\Model\Entity\Term findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Term patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Term> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Term|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Term saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Term>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Term>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Term>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Term> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Term>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Term>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Term>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Term> deleteManyOrFail(iterable $entities, array $options = [])
 */
class TermsTable extends \CakePHPWordpress\Model\Table\WordpressAbstract\AbstractTermsTable
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

        $this->setTable('terms');
        $this->setDisplayField('name');
        $this->setPrimaryKey('term_id');
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
            ->scalar('name')
            ->maxLength('name', 200)
            ->notEmptyString('name');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 200)
            ->notEmptyString('slug');

        $validator
            ->notEmptyString('term_group');

        return $validator;
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
