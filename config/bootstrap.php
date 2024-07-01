<?php

// Load configuration from CakePHPWordpress.php located in APP/config
\Cake\Core\Configure::load($this->getName(), 'default');

// Pick the first configured blog to be the default blog
$blogList = \Cake\Core\Configure::read('CakePHPWordpress.blogList');
$blogSymbol = array_keys($blogList)[0];
\Cake\Core\Configure::write('CakePHPWordpress.defaultBlog', $blogSymbol);

// Preload options
$blog = new \CakePHPWordpress\Connector();
$options = $blog->Options->find('list', [
    'keyField' => 'option_name',
    'valueField' => 'option_value',
])->toArray();
// TODO store options per blog symbol (requires Entities to know their symbol)
\Cake\Core\Configure::write('CakePHPWordpress.Options', $options);

// Preload all categories; 'threaded' arranges by hierarchy
$categories = $blog->Categories->find('threaded', [
    'parentField' => 'parent' // default is 'parent_id', Wordpress uses 'parent'
])->toArray();
// TODO store categories per blog symbol (requires Entities to know their symbol)
\Cake\Core\Configure::write('CakePHPWordpress.Categories', $categories);
