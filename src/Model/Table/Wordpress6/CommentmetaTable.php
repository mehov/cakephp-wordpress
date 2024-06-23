<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Table\Wordpress6;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Commentmeta Model
 *
 * @method \CakePHPWordpress\Model\Entity\Commentmetum newEmptyEntity()
 * @method \CakePHPWordpress\Model\Entity\Commentmetum newEntity(array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Commentmetum> newEntities(array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Commentmetum get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \CakePHPWordpress\Model\Entity\Commentmetum findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Commentmetum patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Commentmetum> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Commentmetum|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Commentmetum saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Commentmetum>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Commentmetum>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Commentmetum>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Commentmetum> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Commentmetum>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Commentmetum>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Commentmetum>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Commentmetum> deleteManyOrFail(iterable $entities, array $options = [])
 */
class CommentmetaTable extends \CakePHPWordpress\Model\Table\WordpressAbstract\AbstractCommentmetaTable
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

        $this->setTable('commentmeta');
        $this->setDisplayField('meta_id');
        $this->setPrimaryKey('meta_id');
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
            ->notEmptyString('comment_id');

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
