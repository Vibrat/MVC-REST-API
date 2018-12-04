<?php

/**
 * This class is created to perform API Call
 * 
 * (c) All rights reserved, Lam Nguyen | lam.nguyen.mr@outlook.com
 * 
 * Product: MVC API Package
 */


use System\Model\Controller;


/**
 * PizzaController API. This will call index() if action is not declared in url
 * 
 * @Url: \index.php?api=get\pizza\barg
 * @Flow\Scope("singleton")
 * @return Json 
 */
class PizzaController extends Controller {


    /**
     * Pizza API: Get Pizza information
     * 
     * @return Json 
     * @api
     */
    public function barg() {

       $this->model->load('pizza/pizza.php'); 
    
       $query = $this->db->query("SELECT COUNT(*) as total FROM `" . DB_PREFIX . "pizza`");

       $this->json->sendBack($query->rows());
    }

    public function login() {
        $token = $this->user->login([
            'username' => $_POST['username'], 
            'password' => $_POST['password']
            ]);

        $this->json->sendBack([
            'token' => $token 
            ]);
    }

    public function checkLogin() {
        $this->json->sendBack($this->user->getToken());
    }

    public function isLogged() {
        $this->json->sendBack($this->user->isLogged($_GET['token']));
    }
}