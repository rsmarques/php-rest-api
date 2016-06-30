<?php

namespace app\lib\database;

interface DBInterface
{
    // DB connector
    public function connect($params);

    // DB interaction functions
    public function get($id = null);
    public function set($id = null, $params = array());
    public function delete($id = null);
}
