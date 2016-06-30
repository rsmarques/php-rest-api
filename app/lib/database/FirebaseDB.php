<?php

namespace app\lib\database;

use app\lib\Firebase;

class FirebaseDB implements DBInterface
{
    private $firebase;
    private $path;

    public function connect($params)
    {
        $databaseConfig = include(__DIR__ . '/../../config/database.php');
        $firebaseConfig = $databaseConfig['connections']['firebase'];

        $this->firebase = new Firebase\FirebaseLib($firebaseConfig['url'], $firebaseConfig['token']);
        $this->path     = $params['path'];
    }

    public function get($id = null)
    {
        $data   = $this->firebase->get($this->path . $id);

        return (array) json_decode($data);
    }

    public function set($id = null, $params = array())
    {
        $data   = $this->firebase->set($this->path . $id, $params);

        return (array) json_decode($data);
    }

    public function delete($id = null)
    {
        $data   = $this->firebase->delete($this->path . $id);

        return true;
    }
}
