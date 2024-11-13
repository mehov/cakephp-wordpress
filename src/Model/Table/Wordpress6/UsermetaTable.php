<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Table\Wordpress6;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Usermeta Model
 *
 * @method \CakePHPWordpress\Model\Entity\Usermetum newEmptyEntity()
 * @method \CakePHPWordpress\Model\Entity\Usermetum newEntity(array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Usermetum> newEntities(array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Usermetum get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \CakePHPWordpress\Model\Entity\Usermetum findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Usermetum patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Usermetum> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Usermetum|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Usermetum saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Usermetum>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Usermetum>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Usermetum>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Usermetum> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Usermetum>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Usermetum>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Usermetum>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Usermetum> deleteManyOrFail(iterable $entities, array $options = [])
 */
class UsermetaTable extends \CakePHPWordpress\Model\Table\WordpressAbstract\AbstractUsermetaTable
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

        $this->setTable('usermeta');
        $this->setDisplayField('umeta_id');
        $this->setPrimaryKey('umeta_id');
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
            ->notEmptyString('user_id');

        $validator
            ->scalar('meta_key')
            ->maxLength('meta_key', 255)
            ->allowEmptyString('meta_key');

        $validator
            ->scalar('meta_value')
            ->maxLength('meta_value', 4294967295)
            ->allowEmptyString('meta_value');

        return $validator;
    }
}
