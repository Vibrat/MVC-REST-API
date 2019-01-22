<?php
namespace Reducer;

use BaseModel;
use SebastianBergmann\GlobalState\Snapshot;

class SignReducer extends BaseModel {
    
    private $payload = [
        'action'    => 'none',
        'payload'   => []
    ];

    /**
     * Reduce an action 
     * 
     * @param Array $snapshot
     */
    public function reduce(Array $snapshot) 
    {
        switch($snapshot['action']) {
            case SIGN_CHECK_ACCOUNT: 
                
                $data = $this->db->query(sprintf(SIGN_CHECK_ACCOUNT, DB_PREFIX, $snapshot['payload']['username']));     
                $this->payload = array_merge($this->payload, [
                    'action'    => 'SIGN_CHECK_ACCOUNT',
                    'payload'   => $data->row('total')
                ]);
                break;
            case SIGN_CREATE_ACCOUNT:

                $data = $this->db->query(sprintf(SIGN_CREATE_ACCOUNT, DB_PREFIX, $snapshot['payload']['username'], $snapshot['payload']['password']));        
                $this->payload = array_merge($this->payload, [
                    'action'    =>  'SIGN_CREATE_ACCOUNT',
                    'payload'   =>  $data->rowsCount()
                ]);

                break;
            case SIGN_DELETE_ACCOUNT:
                $data = $this->db->query(sprintf(SIGN_DELETE_ACCOUNT, DB_PREFIX, $snapshot['payload']['username']));
                
                $this->payload = array_merge([
                    'action'    =>  'SIGN_DELETE_ACCOUNT',
                    'payload'   => $data->rowsCount()
                ]);
                break;
        }

        return $this->payload;
    }
}