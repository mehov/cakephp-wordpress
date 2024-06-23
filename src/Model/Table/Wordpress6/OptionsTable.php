<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Table\Wordpress6;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Options Model
 *
 * @method \CakePHPWordpress\Model\Entity\Option newEmptyEntity()
 * @method \CakePHPWordpress\Model\Entity\Option newEntity(array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Option> newEntities(array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Option get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \CakePHPWordpress\Model\Entity\Option findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Option patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Option> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Option|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Option saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Option>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Option>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Option>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Option> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Option>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Option>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Option>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Option> deleteManyOrFail(iterable $entities, array $options = [])
 */
class OptionsTable extends \CakePHPWordpress\Model\Table\WordpressAbstract\AbstractOptionsTable
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

        $this->setTable('options');
        $this->setDisplayField('option_name');
        $this->setPrimaryKey('option_id');
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
            ->scalar('option_name')
            ->maxLength('option_name', 191)
            ->notEmptyString('option_name')
            ->add('option_name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('option_value')
            ->maxLength('option_value', 4294967295)
            ->requirePresence('option_value', 'create')
            ->notEmptyString('option_value');

        $validator
            ->scalar('autoload')
            ->maxLength('autoload', 20)
            ->notEmptyString('autoload');

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
        $rules->add($rules->isUnique(['option_name']), ['errorField' => 'option_name']);

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
