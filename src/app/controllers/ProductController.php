<?php

use Phalcon\Mvc\Controller;
use Phalcon\Escaper;

class ProductController extends Controller
{
    public function indexAction()
    {
        $color = array('red', 'black');
        $size = array('small', 'medium');
        $fabric = array('cotton', 'silk');
        $data = array($color, $size, $fabric);
        $this->view->data = $data;
    }

    public function addAction()
    {
        $count=0;
        $countMetaData=0;
        $collection = $this->mongo->products;
        if (!$_POST['additionaldata'][0]=='') {
            $count = count($_POST['additionaldata']);
        }
        if (!$_POST['metaKey'][0]=='') {
            $countMetaData = count($_POST['metaKey']);
        }
        $meta = [];
        $variations = [];
        if ($count == 0) {
            $meta[0] = "no meta data found";
        }
        if ($countMetaData == 0) {
            $variations[0] = 'no variations found';
        }
        for ($i = 0; $i < $count; $i++) {
            $key = $_POST['additionaldata'][$i];
            $value = $_POST['additionaldatavalue'][$i];
            $meta[$key] = $value;
        }
        for ($i = 0; $i < $countMetaData; $i++) {
            $key = $_POST['metaKey'][$i];
            $value = $_POST['metaValue'][$i] . "=>" . $_POST['metaValuePrice'][$i];
            $variations[$key] = $value;
        }
        $insertElement = $collection->insertOne(
            [
                'name' => $this->request->get('name'),
                'desc' => $this->request->get('desc'),
                'price' => $this->request->get('price'),
                'stock' => $this->request->get('stock'),
                'additionalData' => $meta,
                'variations' => $variations,
            ]
        );
        if ($insertElement->getInsertedCount() == 1) {
            echo "<h1>Data Inserted Succesfully</h1>";
        } else {
            echo "<h1>Error........... ! Please Try Again</h1>";
        }
        echo "<a href='index' class='btn btn-info'>Back</a>";
    }

    public function viewAction()
    {
        $collection = $this->mongo->products;
        $data = "";
        if ($this->request->get('search') == 'Search' && $this->request->get('searchName') != '') {
            $res = $collection->findOne(['name' => $this->request->get('searchName')]);
            $data = "<tr><td>$res->name</td><td>$res->desc</td>
                <td>$res->price</td><td>$res->stock</td>";
            $data .= "<td><a href='fullDetail?id=$res->_id'>More Details</a></td>
            <td><a href='update?id=$res->_id'class='btn btn-warning mx-1' >Update</a>
            <button class='btn btn-danger'  onclick=deleteData('$res->_id')>Delete</button></td></tr>";
        } else {
            $result = $collection->find();
            foreach ($result as $value) {
                $data .= "<tr><td>$value->name</td><td>$value->desc</td>
                <td>$value->price</td><td>$value->stock</td>
                <td><a href='fullDetail?id=$value->_id'>More Details</a>
                </td><td><a href='update?id=$value->_id' class='btn btn-warning mx-1' >Update</a>
            <button class='btn btn-danger'  onclick=deleteData('$value->_id')>Delete</button></td></tr>";
            }
        }
        $this->view->data = $data;
    }

    public function deleteAction()
    {
        $id = $this->request->get('id');
        $collection = $this->mongo->products;
        $collection->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
    }

    public function updateAction()
    {
        $id = $this->request->get('id');
        $collection = $this->mongo->products;
        $result = $collection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
        $this->view->data = $result;
    }

    public function updateDataAction()
    {
        $escaper = new Escaper();
        $collection = $this->mongo->products;
        $collection->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($this->request->getPost('id'))],
            ['$set' => [
                'name' => $escaper->escapeHtml($this->request->getPost('name')),
                'desc' =>  $escaper->escapeHtml($this->request->getPost('desc')),
                'price' => $escaper->escapeHtml($this->request->getPost('price')),
                'stock' => $escaper->escapeHtml($this->request->getPost('stock')),
            ]]
        );
        $this->response->redirect('product/view');
    }

    public function fullDetailAction()
    {
        $id = $this->request->get('id');
        $collection = $this->mongo->products;
        $res = $collection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
        $this->view->data = $res;
    }
}
