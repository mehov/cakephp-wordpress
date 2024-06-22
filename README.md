## CakePHP to Wordpress connector

```
composer require mehov/cakephp-wordpress:dev-master
```

```
cp vendor/mehov/cakephp-wordpress/config/CakePHPWordpress-dist.php config/CakePHPWordpress.php
// don't forget to edit config/CakePHPWordpress.php
```

```
// src/Application.php::bootstrap()
$this->addPlugin(\CakePHPWordpress\Plugin::class, ['bootstrap' => true, 'routes' => false]);
```

```
// src/Controller/AppController.php::initialize()
$blogList = \Cake\Core\Configure::read('CakePHPWordpress.blogList');
$blogSymbol = array_keys($blogList)[0];
\Cake\Core\Configure::write('CakePHPWordpress.defaultBlog', $blogSymbol);
```

```
// Wherever you need to get the Wordpress posts
$blog = new \CakePHPWordpress\Connector();
$query = $blog->Posts->find('all', []);
$query = $query->all();
```