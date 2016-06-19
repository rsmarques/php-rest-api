<?php

namespace app\models;

class ApiModel
{
    protected $firebase;

    public function __construct()
    {
        $firebaseConfig = include('../app/config/firebase.php');
        $this->firebase = new \Firebase\FirebaseLib($firebaseConfig['url'], $firebaseConfig['token']);
    }
}
