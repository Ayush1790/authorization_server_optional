<?php

use Phalcon\Mvc\Controller;


class OrderController extends Controller
{
    public function indexAction()
    {
        $collection = $this->mongo->products;
        $data = $collection->find();
        $this->view->data = $data;
    }

    public function addAction()
    {
        $collection = $this->mongo->order;
        $collection->insertOne(
            [
                'customer_name' => $_POST['name'],
                'address' => $_POST['address'],
                'zip' => $_POST['zip'],
                'product' => $_POST['product'],
                'variation' => $_POST['variations'],
                'qty' => $_POST['qty'],
                'status' => 'processing',
                'date' => date("Y/m/d"),
            ]
        );
    }

    public function viewAction()
    {
        $collection = $this->mongo->order;
        $data = "";
        if ($this->request->get('search') &&
        ($this->request->get('startDate') != '' ||$this->request->get('time') != 'select any one')) {
            $start = date("Y/m/d");
            if ($this->request->get('time') == 'today') {
                $end = date('Y/m/d', strtotime('+1 days'));
            } elseif ($this->request->get('time') == 'this_week') {
                $end = date('Y/m/d', strtotime('-7 days'));
            } elseif ($this->request->get('time') == 'this_month') {
                $end = date('Y/m/d', strtotime('-30 days'));
            } else {
                $start = $this->request->get('startDate');
                $end = $this->request->get('endDate');
            }
            $result = $collection->find([
                'status' => $this->request->get('status'),
                '$and' => [['date' => ['$gte' => $start]], ['date' => ['$lte' => $end]]]
            ]);
        } else {
            $result = $collection->find();
        }
        foreach ($result as $value) {
            $data .= "<tr><td>$value->customer_name</td><td>$value->address</td>
            <td>$value->zip</td><td>$value->product</td>
            <td>$value->variation</td><td>$value->qty</td>
            <td>$value->status</td><td>$value->date</td>
            </td><td><a href='view?id=$value->_id&&update=update' class='btn btn-warning mx-1' name='update'>Update</a>
        <a href='deleteData?id=$value->_id' class='btn btn-danger'>Delete</a></td></tr>";
        }
        $this->view->data = $data;
    }

    public function deleteDataAction()
    {
        $id = $this->request->get('id');
        $collection = $this->mongo->order;
        $collection->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
        $this->response->redirect('order/view');
    }
    public function updateAction()
    {
        $collection = $this->mongo->order;
        $collection->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($this->request->getPost('id'))],
            ['$set' => [
                'status' =>  $this->request->getPost('status'),
            ],]
        );
        $this->response->redirect('order/view');
    }
}
