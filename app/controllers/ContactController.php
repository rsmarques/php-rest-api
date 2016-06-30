<?php

namespace app\controllers;

use app\http\Request;
use app\http\Response;

use app\models\Contact;

/*
 * Controller that manages a list of contacts in a RESTful way
 *
 */
class ContactController
{

    public function get($id = null)
    {
        $contact    = new Contact;

        if (empty($id)) {
            // no id received, getting all contacts
            $data   = $contact->getAll();
        } else {
            $data   = $contact->get($id);
        }

        return Response::json($data);
    }

    public function create($id = null)
    {
        // contact params come from request
        $request    = Request::createFromGlobals();
        $params     = $request->getContent();

        $contact    = new Contact;
        $data       = $contact->create($id, $params);

        return Response::json($data);
    }

    public function update($id = null)
    {
        // contact params come from request
        $request    = Request::createFromGlobals();
        $params     = $request->getContent();

        $contact    = new Contact;

        if (empty($id)) {
            // no id received, updating entire collection
            $data   = $contact->updateAll($params);
        } else {
            $data   = $contact->update($id, $params);
        }

        return Response::json($data);
    }

    public function delete($id = null)
    {
        $contact    = new Contact;

        if (empty($id)) {
            // no id received, deleting entire collection
            $data   = $contact->deleteAll();
        } else {
            $data   = $contact->delete($id);
        }

        return Response::json($data);
    }
}
