<?php

namespace app\models;

class Contact extends ApiModel
{
    const FIREBASE_PATH  = '/contacts/';

    public function get($id)
    {
        $contact    = $this->firebase->get(self::FIREBASE_PATH . $id);

        return json_decode($contact);
    }

    public function getAll()
    {
        $contacts   = $this->firebase->get(self::FIREBASE_PATH);

        return json_decode($contacts);
    }

    public function create($id = null, $params = array())
    {
        if (empty($id)) {
            // generating id to next entry
            $contacts           = $this->getAll();
            if (empty($contacts)) {
                $id             = 1;
            } else {
                $contactIds     = array_keys((array) $contacts);
                $lastContactId  = end($contactIds);

                $id             = ++$lastContactId;
            }
        }

        $contact    = $this->firebase->set(self::FIREBASE_PATH . $id, $params);

        return json_decode($contact);
    }

    public function update($id, $params)
    {
        $contact    = $this->firebase->set(self::FIREBASE_PATH . $id, $params);

        return json_decode($contact);
    }

    public function updateAll($params)
    {
        $contacts   = $this->firebase->set(self::FIREBASE_PATH, $params);

        return json_decode($contacts);
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
