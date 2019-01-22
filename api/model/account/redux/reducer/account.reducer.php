<?php
namespace Reducer;

use BaseModel;
/**
 * Account Reducer
 * 
 * @param $snapshot contains payload and action 
 * @example [ 'action' => 'AddNewAccount', 'payload' => [] ]
 */
 class AccountReducer extends BaseModel 
 {
    public $payload = [
        'action'    => 'none',
        'payload'   => []
    ];

    public function reduce($snapshot) {

        switch($snapshot['action']) {
            case ACCOUNT_ADD_NEW:
                
                $this->payload = array_merge($snapshot['payload'], $this->payload);
                break;
            case ACCOUNT_GROUP_LIST:
        
                $data  =  $this->db->query(sprintf(ACCOUNT_GROUP_LIST, DB_PREFIX, $snapshot['payload']['id']))->row();
                $this->payload = array_merge($this->payload, [
                    'action'    =>  'ACCOUNT_GROUP_LIST',
                    'payload'   =>  $data
                ]);
                break;
        }

        return $this->payload;
    }

    public function add_service() {
        
    }
 }