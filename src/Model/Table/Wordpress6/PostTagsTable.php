<?php

namespace CakePHPWordpress\Model\Table\Wordpress6;

class PostTagsTable extends TermTaxonomyTable
{

    public function beforeFind($event, $query, $options, $primary)
    {
        if ($primary && $query->getRepository() !== $this) {
            return;
        }
        $query->contain('Terms');
        $query->where([
            $query->getRepository()->getAlias().'.taxonomy' => 'post_tag',
        ]);
    }

}