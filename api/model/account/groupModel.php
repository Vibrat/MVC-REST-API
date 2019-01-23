<?php
require 'redux/redux.php';

use Reducer\AccountReducer;

/**
 * Model for Group Permissions
 */
class GroupModel extends AccountReducer
{

    /**
     * Create new group in users_group
     * 
     * @param Array $data [
     *      'name' => 'VIP123213', 
     *      'permission' => {"api": ["post/account/group-permission/"]}
     * ]
     * @return Array $payload
     */
    public function newGroup(array $data)
    {
        return $this->reduce([
            'action'    => ACCOUNT_GROUP_CREATE,
            'payload'   => [
                'name'          => $data['name'],
                'permission'    => $data['permission']
            ]
        ]);
    }

    /**
     * Count number of groups by name
     * 
     * @param String $group_name
     */
    public function countGroup(String $group_name)
    {

        return $this->reduce([
            'action'    => ACCOUNT_GROUP_NUM_BY_NAME,
            'payload'   => [
                    'name'  => $group_name
                ]
        ])['payload'];
    }

    /**
     * List Permissions of a Group
     * 
     * @param Int id 
     */
    public function listPermissions($id)
    {
        $response = $this->reduce([
            'action'    => ACCOUNT_PERMISSION_LIST,
            'payload'   => [
                'id'    =>  $id
            ]
        ]);

        return $response['payload']['permission'];
    }

    /**
     * Add a record to table users_permission
     * 
     * @param Array $data ['userId' => 1, 'groupId' => 2]
     * @return Number
     */
    public function addUserToGroup($data)
    {

        if ($this->reduce([
            'action'    => ACCOUNT_GROUP_NUM,
            'payload'   => [
                'id'    => $data['groupId']
            ]   
        ])['payload']) 
        {
            if (!$this->reduce([
                'action'    => ACCOUNT_PERMISSION_NUM,
                'payload'   =>  [
                    'userId'    => $data['userId'],
                    'groupId'   => $data['groupId']
                ]
            ])['payload']) {
                $records = $this->reduce([
                    'action'    => ACCOUNT_PERMISSION_ADD,
                    'payload'   => [
                        'userId'    => $data['userId'],
                        'groupId'   => $data['groupId']
                    ]
                ]);

                return $records;
            }
        }

        return;
    }

    /**
     * Query list of groups from database
     * 
     * @param  String $id 
     * @return Array 
     */
    public function listGroups($id)
    {
        $this->add_service('AccountService');
        $payload = $this->reduce([
            'action'    => ACCOUNT_GROUP_LIST,
            'payload'   => [
                'id' => $id
            ]
        ]);

        return $payload;
    }

    /**
     * Delete a Group in users_group
     * 
     * @param String $name
     */
    public function delete(String $name) 
    {
        $payload = $this->reduce([
            'action'    => ACCOUNT_GROUP_DELETE,
            'payload'   => [
                'name'  => $name
            ]
        ]);

        return $payload['payload'];
    }
}