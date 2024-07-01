<?php

namespace CakePHPWordpress\Model\Table\WordpressAbstract;

abstract class AbstractTermTaxonomyTable extends \CakePHPWordpress\Model\Table\PluginTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->belongsTo('Terms', [
            'foreignKey' => 'term_id',
            'joinType' => 'INNER',
            'className' => 'CakePHPWordpress.Terms',
        ]);
    }


}
