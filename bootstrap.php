<?php

require 'core/ClassLoader.php';

$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__).'/core'); // dirname: 親ディレクトリのパスを返す
$loader->registerDir(dirname(__FILE__).'/models');
$loader->regster();
