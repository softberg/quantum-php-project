<?php 

namespace Modules\Main\Controllers;
use Quantum\Mvc\Qt_Controller;
use Quantum\Http\Request;
use Quantum\Http\Response;
use Modules\Main\Models\User;

class MainController extends Qt_Controller
{
    public function __construct() {
        $this->setLayout('layouts/main');
    }
    
    public function __before() {
       // echo "Before Action";
    }
    
    public function __after() {
        //echo "After Action";
    }

    public function homeAction() {
        
        $data = [
            'firstname'=>'Alen', 
            'lastname'=>'Delon', 
            'email'=>'delon@mail.com', 
            'pass'=>'123456'
        ];
        
        $user = $this->modelFactory('User');
        
        $user->findOne(2);
//        $user->findOneBy('email', 'peto@mail.com');
        
        echo "<pre>"; print_r($user);die;
        
//        $user->create();
//        $user->fillObjectProps($data);
//        $user->save();
    
        
        
//        $user->findOne(11);
//        $user->email = "a.delon@mail.com";
//        $user->save();
        
//          $user->findOne(9);
//          $user->delete();
        

        
        $this->render('home', [
            'a' => 'foo',
            'b' => 'bar'
        ]);

    }
    
    public function greatingsAction($name, $number) {
        $car = 'Mersedes';
        $this->render('greatings', [
            'name' => $name,
            'num' => $number,
            'car' => $car,
        ]);
    }
    
    public function indexAction() {
        
        $this->render('index', [
            'a' => 'foo',
            'b' => 'bar'
        ]);
    }
}