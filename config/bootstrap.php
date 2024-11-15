<?php

// Load configuration from CakePHPWordpress.php located in APP/config
\Cake\Core\Configure::load($this->getName(), 'default');

// Pick the first configured blog to be the default blog
$blogList = \Cake\Core\Configure::read('CakePHPWordpress.blogList');
$blogSymbol = array_keys($blogList)[0];
\Cake\Core\Configure::write('CakePHPWordpress.defaultBlog', $blogSymbol);

$blog = new \CakePHPWordpress\Connector();

$prefetch = array(
    'Options' => function() use($blog) {
        return $blog->Options->find(
            'list', keyField: 'option_name', valueField: 'option_value'
        )->toArray();
    },
    'Categories' => function() use($blog) {
        return $blog->Categories->find(
            'threaded', // arranges by hierarchy
            parentField: 'parent' // CakePHP expects 'parent_id', Wordpress uses 'parent'
        )->toArray();
    },
    'Pages' => function() use($blog) {
        return $blog->Posts->find('pages')->find('published')->find(
            'threaded', // arranges by hierarchy
            parentField: 'post_parent' // CakePHP expects 'parent_id', Wordpress uses 'parent'
        )->toArray();
    },
);

foreach ($prefetch as $tableAlias => $callback) {
    $key = sprintf('%s.%s', $this->getName(), $tableAlias);
    $values = \Cake\Cache\Cache::read($key);
    if (null === $values) {
        $values = $callback();
        \Cake\Cache\Cache::write($key, $values);
    }
    // TODO store per blog symbol (requires Entities to know their symbol)
    \Cake\Core\Configure::write($key, $values);
}
