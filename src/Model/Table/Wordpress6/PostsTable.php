<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Table\Wordpress6;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Posts Model
 *
 * @method \CakePHPWordpress\Model\Entity\Post newEmptyEntity()
 * @method \CakePHPWordpress\Model\Entity\Post newEntity(array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Post> newEntities(array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Post get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \CakePHPWordpress\Model\Entity\Post findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Post patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Post> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Post|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Post saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Post>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Post>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Post>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Post> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Post>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Post>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Post>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Post> deleteManyOrFail(iterable $entities, array $options = [])
 */
class PostsTable extends \CakePHPWordpress\Model\Table\WordpressAbstract\AbstractPostsTable
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

        $this->setTable('wp_posts');
        $this->setDisplayField('post_status');
        $this->setPrimaryKey('ID');
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
            ->notEmptyString('post_author');

        $validator
            ->dateTime('post_date')
            ->notEmptyDateTime('post_date');

        $validator
            ->dateTime('post_date_gmt')
            ->notEmptyDateTime('post_date_gmt');

        $validator
            ->scalar('post_content')
            ->maxLength('post_content', 4294967295)
            ->requirePresence('post_content', 'create')
            ->notEmptyString('post_content');

        $validator
            ->scalar('post_title')
            ->requirePresence('post_title', 'create')
            ->notEmptyString('post_title');

        $validator
            ->scalar('post_excerpt')
            ->requirePresence('post_excerpt', 'create')
            ->notEmptyString('post_excerpt');

        $validator
            ->scalar('post_status')
            ->maxLength('post_status', 20)
            ->notEmptyString('post_status');

        $validator
            ->scalar('comment_status')
            ->maxLength('comment_status', 20)
            ->notEmptyString('comment_status');

        $validator
            ->scalar('ping_status')
            ->maxLength('ping_status', 20)
            ->notEmptyString('ping_status');

        $validator
            ->scalar('post_password')
            ->maxLength('post_password', 255)
            ->notEmptyString('post_password');

        $validator
            ->scalar('post_name')
            ->maxLength('post_name', 200)
            ->notEmptyString('post_name');

        $validator
            ->scalar('to_ping')
            ->requirePresence('to_ping', 'create')
            ->notEmptyString('to_ping');

        $validator
            ->scalar('pinged')
            ->requirePresence('pinged', 'create')
            ->notEmptyString('pinged');

        $validator
            ->dateTime('post_modified')
            ->notEmptyDateTime('post_modified');

        $validator
            ->dateTime('post_modified_gmt')
            ->notEmptyDateTime('post_modified_gmt');

        $validator
            ->scalar('post_content_filtered')
            ->maxLength('post_content_filtered', 4294967295)
            ->requirePresence('post_content_filtered', 'create')
            ->notEmptyString('post_content_filtered');

        $validator
            ->notEmptyString('post_parent');

        $validator
            ->scalar('guid')
            ->maxLength('guid', 255)
            ->notEmptyString('guid');

        $validator
            ->integer('menu_order')
            ->notEmptyString('menu_order');

        $validator
            ->scalar('post_type')
            ->maxLength('post_type', 20)
            ->notEmptyString('post_type');

        $validator
            ->scalar('post_mime_type')
            ->maxLength('post_mime_type', 100)
            ->notEmptyString('post_mime_type');

        $validator
            ->notEmptyString('comment_count');

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
