<?php

namespace app\models;

use app\lib\database\DBConnector;

class Contact
{
    const FIREBASE_PATH  = '/contacts/';
    private $dbConnection;

    public function __construct()
    {
        $this->dbConnection     = DBConnector::getDefaultDatabase();
        $this->dbConnection->connect(array('path' => self::FIREBASE_PATH));
    }

    public function get($id)
    {
        $contact    = $this->dbConnection->get($id);

        return $contact;
    }

    public function getAll()
    {
        $contacts   = $this->dbConnection->get(null);

        return $contacts;
    }

    public function create($id = null, $params = array())
    {
        if (empty($id)) {
            // generating id to next entry
            $id     = uniqid();
        }

        $contact    = $this->dbConnection->set($id, $params);

        return $contact;
    }

    public function update($id, $params)
    {
        $contact    = $this->dbConnection->set($id, $params);

        return $contact;
    }

    public function updateAll($params)
    {
        $contacts   = $this->dbConnection->set(null, $params);

        return $contacts;
    }

    public function delete($id)
    {
        $response   = $this->dbConnection->delete($id);

        return $response;
    }

    public function deleteAll()
    {
        $response   = $this->dbConnection->delete(null);

        return $response;
    }
}
