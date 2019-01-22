<?php

class AccountService {

    /**
     * Action Handler
     * 
     * @param  String $action
     * @return Array $payload
     */
    public function call($action = '') {
        switch ($action) {
            case ACCOUNT_GROUP_LIST: 
                echo "service called";
                break;    
        }

        return ['action' => 'service', 'payload' => ['id' => 'something here']];
    }

}