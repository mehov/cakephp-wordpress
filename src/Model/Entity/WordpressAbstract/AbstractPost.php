<?php

namespace CakePHPWordpress\Model\Entity\WordpressAbstract;

abstract class AbstractPost extends \CakePHPWordpress\Model\Entity\PluginEntity
{

    public function _getUrl()
    {
        $permalink_structure = \Cake\Core\Configure::read(
            'CakePHPWordpress.Options.permalink_structure'
        );
        $placeholders = [
            '%year%',
            '%monthnum%',
            '%day%',
            '%hour%',
            '%minute%',
            '%second%',
            '%post_id%',
            '%postname%',
            '%category%',
            '%author%',
        ];
        $values = [
            $this->post_date->format('Y'),
            $this->post_date->format('m'),
            $this->post_date->format('d'),
            $this->post_date->format('H'),
            $this->post_date->format('i'),
            $this->post_date->format('s'),
            $this->ID,
            $this->post_name,
            'categ',
            $this->post_author,
        ];
        $url = str_replace($placeholders, $values, $permalink_structure);
        return \Cake\Routing\Router::url($url);
    }

}
