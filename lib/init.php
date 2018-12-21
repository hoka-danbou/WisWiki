<?php
define('DEBUG', false);

define('MAIN_PAGE', 'メインページ');
define('SIDEBAR_PAGE', 'サイドバー');

require_once(__DIR__.'/../vendor/autoload.php');
require_once('App.php');
require_once('models/item.php');
require_once('models/edit_lock.php');
require_once('LinkMaker.php');
require_once('LockManager.php');

ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory(__DIR__ . '/models');

    if(substr(PHP_OS,0,3) == 'WIN') {
        $cfg->set_connections(
          [
            'development' => 'sqlite://windows/'.__DIR__.'/../data/wyswiki-devel.sqlite3',
            'production'  => 'sqlite://windows/'.__DIR__.'/../data/wyswiki.sqlite3',
          ]
        );
    }else{
        $cfg->set_connections(
          [
            'development' => 'sqlite://unix'.__DIR__.'/../data/wyswiki-devel.sqlite3',
            'production'  => 'sqlite://unix'.__DIR__.'/../data/wyswiki.sqlite3',
          ]
        );
    }
    $cfg->set_default_connection('production');
});
