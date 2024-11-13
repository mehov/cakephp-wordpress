# CakePHP to Wordpress connector

### Installation

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

### Usage

#### Automatic - out of the box

Your posts should automatically be available.

The plugin will fetch your *Permalink structure* (as defined in your Wordpress admin - see `/wp-admin/options-permalink.php`) and connect it as a route in your host CakePHP application.

For example:

- **If you *Permalink structure* is `/myblog/%year%/%postname%/`**
  
  The plugin will identify that `myblog` is the base path and will be listening for everything under that path.
- **If you *Permalink structure* is `/%postname%/`**
  
  The plugin will be listening for everything. Conflicts may happen. Check your `APP/config/routes.php` to make sure no other route is hijacking the path where you expect your Wordpress content.

#### Manually fetching content

```
// Wherever you need to get the Wordpress posts
$blog = new \CakePHPWordpress\Connector();
$query = $blog->Posts->find('all', []);
$query = $query->all();
```
