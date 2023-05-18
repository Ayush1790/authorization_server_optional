<?php

namespace Multi\frontend\Controllers;

use Phalcon\Mvc\Controller;


class ProductViewController extends Controller
{
    public function indexAction()
    {
        $collection = $this->mongo->products;
        $result = $collection->find();
        foreach ($result as $value) {
            $data .= "<tr><td>$value->name</td><td>$value->desc</td>
                <td>$value->price</td><td>$value->stock</td>
                <td><a href='productView/fullDetail?id=$value->_id'>More Details</a>
                </td></tr>";
        }
        $this->view->data = $data;
    }

    public function fullDetailAction()
    {
        $collection = $this->mongo->products;
        $result = $collection->findOne(['_id'=> new \MongoDB\BSON\ObjectId($this->request->get('id'))]);
        $this->view->data=$result;
    }
}
