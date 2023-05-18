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
        $email = $this->request->get('email');
        $pswd = $this->request->get('pswd');
        $db = $this->mongo->users;
        $res = $db->findOne(['email' => $email, 'password' => $pswd]);
        if (empty($res)) {
            echo "<h1>Wrong UserId Or Password</h1>";
            echo "<br><a href='/login' class='btn btn-info'>Try Again</a>";
        } else {
            $this->response->redirect('../admin/product/index');
        }
    }
}
