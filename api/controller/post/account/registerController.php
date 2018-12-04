<?php 
/**
 * Regster Token Manager
 */

use \System\Model\Controller;

class RegisterController extends Controller {
    
    /**
     * Create new Account
     * 
     * API: /post/account/newAccount
     * Method: POST
     */
    public function newAccount() {
        $this->model->load('account/account');
        
        if ($this->validateUser($_POST)) {
            ## validation of users here
            $response = $this->model->account->createAccount($_POST);

            $this->json->sendBack($response);
        }

        ## return false
    }

    private function validateUser($data) {
        ## validate if empty
        if (!isset($data['username']) || !isset($data['password'])) {
            return;
        }

        ## validate if exists
        if ($this->model->account->checkAccount($data['username'])) {
            return;
        }

        ## validate if password egitibility
        if (!(function ($pwd) {
            ## validate password
            if (strlen($pwd) < 8) {
                return;
            }

            return true;
        })($data['password'])) {
            return;
        }

        return true;
    }
} 