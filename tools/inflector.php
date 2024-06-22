<?php

require('vendor/cakephp/cakephp/src/Utility/Inflector.php');

$inflector = new \Cake\Utility\Inflector();

echo $inflector::{$argv[1]}($argv[2]).PHP_EOL;