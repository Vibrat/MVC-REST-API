<?php
namespace Reducer;

use BaseModel;
use AccountService;

/**
 * Account Reducer
 * 
 * @param $snapshot contains payload and action 
 * @example [ 'action' => 'AddNewAccount', 'payload' => [] ]
 */
class AccountReducer extends BaseModel
{
    private $reduxService;
    public $payload = [
        'action' => 'none',
        'payload' => []
    ];

    public function reduce($snapshot)
    {

        switch ($snapshot['action'])
        {
            case ACCOUNT_ADD_NEW:

                $this->payload = array_merge($snapshot['payload'], $this->payload);
                break;
            case ACCOUNT_GROUP_LIST:

                $data = $this->db->query(sprintf(ACCOUNT_GROUP_LIST, DB_PREFIX, $snapshot['payload']['id']))->row();
                $this->payload = array_merge($this->payload, [
                    'action' => 'ACCOUNT_GROUP_LIST',
                    'payload' => $data
                ]);
                break;
        }

        if ($this->reduxService instanceof AccountService) 
        {
   
            $this->payload = [
                'action'    => $this->payload['action'],
                'payload'   => array_merge($this->payload['payload'], $this->reduxService->call($snapshot['action'])['payload'])
            ];
        }

        return $this->payload;
    }

    /**
     * Add a Service to Reducer
     * 
     * @param String $name
     */
    public function add_service($name)
    {
        return $this->reduxService = new $name();
    }
}