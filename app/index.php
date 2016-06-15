<?php

require_once("app/lib/SplClassLoader.php");

$loader = new SplClassLoader('app', __DIR__);
$loader->register();
