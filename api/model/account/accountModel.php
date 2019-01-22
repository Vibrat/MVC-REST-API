<?php

require 'redux/redux.php';

use Reducer\SignReducer;

/**
 * Account Model - Modify DB
 * 
 * Please do not use __construct here
 */
class AccountModel extends SignReducer {
   
    /**
     * Check if account exists
     * 
     * @param String $username
     * @return Number number of records exist
     */
    public function checkAccount($username) {
       return $this->reduce([
           'action'     => SIGN_CHECK_ACCOUNT,
           'payload'    => [
               'username'   => $username
           ]    
       ])['payload'];
    }

    /**
     * Create new Account
     *  
     * @param Array $data ['username' => 'lamnguyen', 'password' => '123456789' ]
     */
    public function createAccount($data) {
        return $this->reduce([
            'action'    => SIGN_CREATE_ACCOUNT,
            'payload'   => [
                'username'      => $data['username'],
                'password'      => password_hash($data['password'], PASSWORD_BCRYPT)
            ]
        ])['payload'];
    }
}