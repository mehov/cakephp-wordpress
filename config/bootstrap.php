<?php

// Load configuration from CakePHPWordpress.php located in APP/config
\Cake\Core\Configure::load($this->getName(), 'default');

// Pick the first configured blog to be the default blog
$blogList = \Cake\Core\Configure::read('CakePHPWordpress.blogList');
$blogSymbol = array_keys($blogList)[0];
\Cake\Core\Configure::write('CakePHPWordpress.defaultBlog', $blogSymbol);
