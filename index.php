<?php

//@header('Content-Type: text/plain');

function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}

ini_set('display_errors', 1); 
error_reporting(E_ALL);

define('STACK_CONFIG_DIR', realpath(dirname($_SERVER['DOCUMENT_ROOT'])).'/shared/config');

require_once STACK_CONFIG_DIR.'/opsworks.php';

$config = new OpsWorks();

ini_set('session.save_handler', 'memcached');
ini_set('session.save_path', $config->memcached->host . ':' . ($config->memcached->port ?: '11211'));

/*
$m = new Memcached();
$m->addServer($config->memcached->host, $config->memcached->port ?: 11211);
$m->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
*/


session_start();

if (empty($_SESSION['foo'])) {
  $_SESSION['foo'] = 0;
}

$_SESSION['foo'] += 10;

echo $_SESSION['foo'];



$con = new MongoClient('mongodb://' . $config->mongodb->host ); //. ':' .  ($config->mongodb->port ?: '27017'));

$db = $con->test_db;

$foo = $db->my_collection->insert([
  'title' => 'test',
  'body' => generateRandomString(12)
]);

$result = $db->my_collection->find();

echo '<pre>Count: ' . $db->my_collection->count() ."\n";
echo 'Matches:';
foreach($result as $k => $v) {
  echo "KEY: {$k}\nVALUE: ";
  var_dump($v);
  echo "\n\n";
}
echo '</pre>';

die();

// read server config and find mongo/memcache parameters

// setup session handler to use memcached

// increment some variable in session, in memcached, and mongodb

// display each of the variables

