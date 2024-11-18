<?php

namespace CakePHPWordpress;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\Utility\Inflector;

class Connector extends Plugin
{

    private $_symbol;
    private $_blogName;
    private $_datasource;
    // Auto-prepended to each table. Particularly useful for multisite networks.
    private $_tablePrefix;
    private $_type;
    private $_localPath;
    private $_externalCss;

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->_symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol($symbol)
    {
        $this->_symbol = $symbol;
    }

    /**
     * @return string
     */
    public function getBlogName()
    {
        return $this->_blogName;
    }

    /**
     * @param string $name
     */
    public function setBlogName($name)
    {
        $this->_blogName = $name;
    }

    /**
     * @return string
     */
    public function getDatasource()
    {
        return $this->_datasource;
    }

    /**
     * @param string $datasource
     */
    public function setDatasource($datasource)
    {
        $this->_datasource = $datasource;
    }

    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->_tablePrefix;
    }

    /**
     * @param string $tablePrefix
     */
    public function setTablePrefix($tablePrefix): void
    {
        $this->_tablePrefix = $tablePrefix;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @return mixed
     */
    public function getLocalPath()
    {
        return $this->_localPath;
    }

    /**
     * @param mixed $localPath
     */
    public function setLocalPath($localPath)
    {
        $this->_localPath = $localPath;
    }

    /**
     * @return array
     */
    public function getExternalCss()
    {
        return $this->_externalCss;
    }

    /**
     * @param array $externalCss URLs to external stylesheets
     */
    public function setExternalCss($externalCss): void
    {
        $this->_externalCss = $externalCss;
    }

    /**
     * Listens to every Model.initialize. Skips models not in this plugin.
     * And every model inside this plugin is prepared.
     *
     * (Prepared means using the right connection and entity class.)
     *
     * @param Cake\Event\Event $event a Model.initialize event
     * @throws \Exception
     */
    public function onEveryModelInitialize($event)
    {
        // Get table class that was just initialised
        $table = $event->getSubject();
        // The registry alias is in Plugin.Model format; split it
        list($plugin, $tableAlias) = pluginSplit($table->getRegistryAlias());
        // Skip right away if this is not a model inside this plugin
        if ($plugin !== $this->getName()) {
            return;
        }
        // Throw exception if no datasource to use has been set yet
        if (empty($this->getDatasource())) {
            throw new \Exception(
                'No data source was set for '.$this->getName()
            );
        }
        /*
         * Make sure the table uses the right entity class
         */
        // If $table comes from association, getAlias() won't get us real table name
        // So use getTable() to get the original database table name instead
        $tableName = $table->getTable();
        // Convert plural table name to singular entity name
        $entityName = Inflector::classify(Inflector::underscore($tableName));
        // Find the entity location
        $entityLocation = $this->_locateModelClass($entityName, 'Entity');
        // Attempt to set the entity class only if it exists
        if ($entityLocation) {
            $table->setEntityClass($entityLocation);
        }
        // Refer $table to an instance of this connector
        $table->setPluginConnector($this);
    }

    /**
     * Connector constructor.
     *
     * @param null $blogSymbol identifies the blog to load from the config
     */
    public function __construct($blogSymbol=null)
    {
        // If none provided, see if a default is configured on the app level
        if (empty($blogSymbol)) {
            $blogSymbol = Configure::read($this->getName().'.defaultBlog');
        }
        // If still nothing, throw an exception
        if (empty($blogSymbol)) {
            throw new InternalErrorException('Blog symbol not provided');
        }
        // Get the blogs list from the config
        $blogs = Configure::read($this->getName().'.blogList');
        if (!$blogs || !is_array($blogs) || empty($blogs)) {
            throw new InternalErrorException('No blogs configured');
        }
        $blogSymbol = strtoupper($blogSymbol);
        if (!isset($blogs[$blogSymbol])) {
            throw new InternalErrorException(sprintf(
                'No blog configured for symbol %s. Available symbols: %s',
                $blogSymbol, implode(', ', array_keys($blogs))
            ));
        }
        $blog = $blogs[$blogSymbol];
        // Set the configured values
        $this->setSymbol($blogSymbol);
        $this->setBlogName($blog['name']);
        $this->setDatasource($blog['datasource']);
        // If overriding model classes on App level place them in this subfolder
        if (!empty($blog['tablePrefix'])) {
            $this->setTablePrefix($blog['tablePrefix']);
        }
        $this->setType($blog['type']);
        // If overriding model classes on App level place them in this subfolder
        if (!empty($blog['localPath'])) {
            $this->setLocalPath($blog['localPath']);
        }
        // If using external CSS files
        if (!empty($blog['externalCss']) && is_array($blog['externalCss'])) {
            $this->setExternalCss($blog['externalCss']);
        }
        // Listen to every Model.initialize (filters irrelevant out later)
        \Cake\Event\EventManager::instance()->on('Model.initialize', [$this, 'onEveryModelInitialize']);
    }

    /**
     * Returns the model table class
     *
     * @param $tableName coming from calls such as $blog->Table
     * @return \Cake\ORM\Table
     */
    public function __get($tableName)
    {
        $tableRegistry = new \Cake\ORM\TableRegistry();
        $tableLocator = $tableRegistry->getTableLocator();
        $tableLocator->clear();// prevent "already exists in the registry" error
        // Make sure table is looked up in blog type folder
        $tableLocator->addLocation('Model/Table/'.$this->getType());
        $tableIdentifier = $this->_locateModelClass($tableName, 'Table');
        // If the table class found is located in App itself
        if (strpos($tableIdentifier, '/') !== false) {
            // Drop table class name, keep only location
            $tableLocation = dirname($tableIdentifier);
            // Make locator aware of location where table class was found
            $tableLocator->addLocation('Model/Table/'.$tableLocation);
            // Since we're on App level, look up table by its name only
            $tableIdentifier = $tableName;
        }
        // Get the table
        $table = $tableLocator->get($tableIdentifier, [
            // Always ensure we're using connection configured for this plugin
            'connectionName' => $this->getDatasource()
            // …otherwise contained table may default to app's connection
        ]);
        if (get_class($table) == 'Cake\ORM\Table') {
            throw new InternalErrorException(sprintf('Requested table %s resolves to generic %s. Make sure a concrete table class exists in %s', $tableName, get_class($table), $this->getPath().'src/Model/Table'));
        }
        return $table;
    }

    /**
     * Tries various class locations for given model (entity or table) name
     *
     * @param string $name model we're looking for; no Table suffix for tables
     * @param string $dir Entity|Table
     * @return string|null CakePHP-compatible class alias in dot notation or not
     */
    private function _locateModelClass($name, $dir)
    {
        // Allow only Entity or Table as $dir values
        if (!in_array($dir, ['Entity', 'Table'])) {
            throw new \Exception(sprintf(
                '$dir needs to be either Entity or Table, received "%s"', $dir
            ));
        }
        // className() looks for exact file names, so add suffix for tables
        $lookup_name = $name . ($dir == 'Table' ? 'Table' : '');
        // If no local path configured, use only entity name, don't look further
        if (empty($this->getLocalPath())) {
            return $name;
        }
        /*
         * From the outside we refer to model files here in this plugin as e.g.:
         * - CakePHPWordpress\Model\Entity\Post
         * - CakePHPWordpress\Model\Table\PostsTable
         * In other words, no mention of version, i.e. if it's Wordpress 5 or 6
         */
        // Build version-agnostic class alias we will be referring to
        $classAlias = sprintf('%s\Model\%s\%s', $this->getName(), $dir, $lookup_name);
        // Internally, the real class path depends on version from the config
        $realClassPath = sprintf('%s\Model\%s\%s\%s', $this->getName(), $dir, $this->getType(), $lookup_name);
        // Only if the alias has not been declared yet and the real class exists
        if (!\class_exists($classAlias) && \class_exists($realClassPath)) {
            //...link the "external" class alias to the real internal class path
            \class_alias($realClassPath, $classAlias);
            // This sets up version-agnostic alias we look up below
            // Actual version is picked up automatically from the config
        }
        // Local path is where overriding classes *may* be stored in App itself
        $path = trim($this->getLocalPath(), '/') . '/' . $lookup_name;
        // See if overriding model class has been created in App itself
        if (\Cake\Core\App::className($path, 'Model/'.$dir)) {
            return $path;
        }
        unset($path);
        // See if the entity exists in this plugin
        if (\Cake\Core\App::className($this->getName().'.'.$lookup_name, 'Model/'.$dir)) {
            return $this->getName().'.'.$name;
        }
        // Finally, just have CakePHP look for an entity by its name
        if (\Cake\Core\App::className($lookup_name, 'Model/'.$dir)) {
            return $name;
        }
        return null;
    }

}
