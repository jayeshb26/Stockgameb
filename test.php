<?php
$mongo = new MongoDB\Client('mongodb://fundeal:fundeal123@13.126.182.204:27017/casino?authSource=admin&w=1');
$dbs = $mongo->listDatabases();

?>