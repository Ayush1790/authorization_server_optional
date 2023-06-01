<?php

namespace Multi\frontend\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->response->redirect('../productView');
    }
}
