<?php

namespace app\controllers;

use app\models\Contact;

class ContactController extends ApiController
{
    public function get($id = null)
    {
        $contact    = new Contact;

        if (empty($id)) {
            $data   = $contact->getAll();
        } else {
            $data   = $contact->get($id);
        }

        return $this->responseWithJson($data);
    }

    public function create($id = null)
    {
        $contact    = new Contact;

        // data params come from POST request
        $params     = $_POST;
        $data       = $contact->create($id, $params);

        return $this->responseWithJson($data);
    }

    public function update($id = null)
    {
        $contact    = new Contact;

        // data params come from PUT request
        parse_str(file_get_contents("php://input"), $params);

        if (empty($id)) {
            $data   = $contact->updateAll($params);
        } else {
            $data   = $contact->update($id, $params);
        }

        return $this->responseWithJson($data);
    }

    public function delete($id = null)
    {
        $contact    = new Contact;

        if (empty($id)) {
            $data   = $contact->deleteAll();
        } else {
            $data   = $contact->delete($id);
        }

        return $this->responseWithJson($data);
    }
}
