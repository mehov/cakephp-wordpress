<?php
declare(strict_types=1);

namespace CakePHPWordpress\Model\Table\Wordpress6;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Comments Model
 *
 * @method \CakePHPWordpress\Model\Entity\Comment newEmptyEntity()
 * @method \CakePHPWordpress\Model\Entity\Comment newEntity(array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Comment> newEntities(array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Comment get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \CakePHPWordpress\Model\Entity\Comment findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Comment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\CakePHPWordpress\Model\Entity\Comment> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Comment|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \CakePHPWordpress\Model\Entity\Comment saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Comment>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Comment>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Comment>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Comment> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Comment>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Comment>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\CakePHPWordpress\Model\Entity\Comment>|\Cake\Datasource\ResultSetInterface<\CakePHPWordpress\Model\Entity\Comment> deleteManyOrFail(iterable $entities, array $options = [])
 */
class CommentsTable extends \CakePHPWordpress\Model\Table\WordpressAbstract\AbstractCommentsTable
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

        $this->setTable('comments');
        $this->setDisplayField('comment_author_email');
        $this->setPrimaryKey('comment_ID');
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
            ->notEmptyString('comment_post_ID');

        $validator
            ->scalar('comment_author')
            ->maxLength('comment_author', 255)
            ->requirePresence('comment_author', 'create')
            ->notEmptyString('comment_author');

        $validator
            ->scalar('comment_author_email')
            ->maxLength('comment_author_email', 100)
            ->notEmptyString('comment_author_email');

        $validator
            ->scalar('comment_author_url')
            ->maxLength('comment_author_url', 200)
            ->notEmptyString('comment_author_url');

        $validator
            ->scalar('comment_author_IP')
            ->maxLength('comment_author_IP', 100)
            ->notEmptyString('comment_author_IP');

        $validator
            ->dateTime('comment_date')
            ->notEmptyDateTime('comment_date');

        $validator
            ->dateTime('comment_date_gmt')
            ->notEmptyDateTime('comment_date_gmt');

        $validator
            ->scalar('comment_content')
            ->requirePresence('comment_content', 'create')
            ->notEmptyString('comment_content');

        $validator
            ->integer('comment_karma')
            ->notEmptyString('comment_karma');

        $validator
            ->scalar('comment_approved')
            ->maxLength('comment_approved', 20)
            ->notEmptyString('comment_approved');

        $validator
            ->scalar('comment_agent')
            ->maxLength('comment_agent', 255)
            ->notEmptyString('comment_agent');

        $validator
            ->scalar('comment_type')
            ->maxLength('comment_type', 20)
            ->notEmptyString('comment_type');

        $validator
            ->notEmptyString('comment_parent');

        $validator
            ->notEmptyString('user_id');

        return $validator;
    }
}
