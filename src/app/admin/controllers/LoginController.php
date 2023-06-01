<?php

namespace Multi\admin\Controllers;

use Phalcon\Mvc\Controller;


class LoginController extends Controller
{
    public function indexAction()
    {
        //redirect to view
    }

    public function loginAction()
    {
        $url = "http://172.22.0.6/token";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = json_decode(curl_exec($ch));
        $email = $this->request->get('email');
        $pswd = $this->request->get('pswd');
        $db = $this->mongo->users;
        $res = $db->findOne(['email' => $email, 'password' => $pswd]);
        if (empty($res)) {
            echo "<h1>Wrong UserId Or Password</h1>";
            echo "<br><a href='/login' class='btn btn-info'>Try Again</a>";
        } else {
            echo "Your access token is -> $response";
            echo "<br><BR>".$this->tag->linkTo('../admin/product/index', 'Proceed Further');
        }
    }
}
