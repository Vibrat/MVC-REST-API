<?php
namespace Reducer;

use BaseModel;
use AccountService;

/**
 * Account Reducer
 * 
 * @param Array $snapshot contains payload and action 
 */
class AccountReducer extends BaseModel
{
    private $service;
    public  $cache = [];
    public  $payload = [
        'action' => 'none',
        'payload' => []
    ];

    /**
     * Redux Reducer
     * 
     * @param  Array $snapshot ['action' => 'none', 'payload' => []]
     * @return Array $payload
     */
    public function reduce($snapshot)
    {

        switch ($snapshot['action']) {
            case ACCOUNT_ADD_NEW:

                $this->payload = array_merge($snapshot['payload'], $this->payload);
                break;
            case ACCOUNT_GROUP_LIST:

                #region check cache
                if ($cache = $this->cache[sprintf(ACCOUNT_GROUP_LIST, DB_PREFIX, $snapshot['payload']['id'])]) 
                {
                    return $cache;
                }
                #endregion

                #region query data
                $data = $this->db->query(sprintf(ACCOUNT_GROUP_LIST, DB_PREFIX, $snapshot['payload']['id']))->row();
                $this->payload = array_merge($this->payload, [
                    'action' => 'ACCOUNT_GROUP_LIST',
                    'payload' => $data->rowsCount()
                ]);
                #end region

                ## save cache
                $this->cache[sprintf(ACCOUNT_GROUP_LIST, DB_PREFIX, $snapshot['payload']['id'])] = $this->payload;
                break;
            case ACCOUNT_GROUP_NUM:
                $data = $this->db->query(sprintf(ACCOUNT_GROUP_NUM, DB_PREFIX, $snapshot['payload']['id']));

                $this->payload = array_merge($this->payload, [
                    'action'    => 'ACCOUNT_GROUP_NUM',
                    'payload'   => $data->row('total')
                ]);
                break;
            case ACCOUNT_GROUP_NUM_BY_NAME:
                $data = $this->db->query(sprintf(ACCOUNT_GROUP_NUM_BY_NAME, DB_PREFIX, $snapshot['payload']['name']));
                
                $this->payload = array_merge($this->payload, [
                    'action'    => 'ACCOUNT_GROUP_NUM_BY_NAME',
                    'payload'   =>  $data->row('total')
                ]);
                break;
                
            case ACCOUNT_GROUP_CREATE: 
                $data = $this->db->query(sprintf(ACCOUNT_GROUP_CREATE, DB_PREFIX, $snapshot['payload']['name'], $snapshot['payload']['permission']));

                $this->payload = array_merge($this->payload, [
                    'action'     => 'ACCOUNT_GROUP_CREATE',
                    'payload'    => $data   
                ]);
                break;

            case ACCOUNT_PERMISSION_NUM: 
                $data = $this->db->query(sprintf(ACCOUNT_PERMISSION_NUM, DB_PREFIX, $snapshot['payload']['userId'], $snapshot['payload']['groupId']));
                
                $this->payload = array_merge($this->payload, [
                    'action'    => 'ACCOUNT_PERMISSION_NUM',
                    'payload'   => $data->row('total')
                ]);
                break;
            case ACCOUNT_PERMISSION_ADD:
                $data = $this->db->query(sprintf(ACCOUNT_PERMISSION_ADD, DB_PREFIX, $snapshot['payload']['userId'], $snapshot['payload']['groupId']));
                
                $this->payload =  array_merge($this->payload, [
                    'action'    => 'ACCOUNT_PERMISSIONS_ADD',
                    'payload'   => $data->rowsCount()
                ]); 
                break;
            case ACCOUNT_PERMISSION_LIST:
                $data = $this->db->query(sprintf(ACCOUNT_PERMISSION_LIST, DB_PREFIX, $snapshot['payload']['id']));
                
                $this->payload = array_merge($this->payload, [
                    'action'    => ACCOUNT_PERMISSION_LIST,
                    'payload'   => $data
                ]);
                break;
        }

        #region check service
        if ($this->service instanceof AccountService) 
        {
            $this->payload = [
                'action' => $this->payload['action'],
                'payload' => array_merge($this->payload['payload'], $this->service->call($snapshot['action'])['payload'])
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
        return $this->service = new $name();
    }
}