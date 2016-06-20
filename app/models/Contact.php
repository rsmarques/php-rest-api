<?php

namespace app\models;

class Contact extends ApiModel
{
    const FIREBASE_PATH  = '/contacts/';

    public function get($id)
    {
        $contact    = $this->firebase->get(self::FIREBASE_PATH . $id);

        return (array) json_decode($contact);
    }

    public function getAll()
    {
        $contacts   = $this->firebase->get(self::FIREBASE_PATH);

        return (array) json_decode($contacts);
    }

    public function create($id = null, $params = array())
    {
        if (empty($id)) {
            // generating id to next entry
            $id     = uniqid();
        }

        $contact    = $this->firebase->set(self::FIREBASE_PATH . $id, $params);

        return (array) json_decode($contact);
    }

    public function update($id, $params)
    {
        $contact    = $this->firebase->set(self::FIREBASE_PATH . $id, $params);

        return (array) json_decode($contact);
    }

    public function updateAll($params)
    {
        $contacts   = $this->firebase->set(self::FIREBASE_PATH, $params);

        return (array) json_decode($contacts);
    }

    public function delete($id)
    {
        $this->firebase->delete(self::FIREBASE_PATH . $id);

        return true;
    }

    public function deleteAll()
    {
        $this->firebase->delete(self::FIREBASE_PATH);

        return true;
    }
}
