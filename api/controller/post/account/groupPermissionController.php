<?php
/**
 * Group Permissions
 * 
 * @method newGroup api=post/account/group-permission/new-group
 */

use System\Model\Controller;

class GroupPermissionController extends Controller {
   
   ## Logic hub 
   public function index() {

        switch($this->http->method()) {
            case 'GET': 

                $this->listGroup();
                break;
            case 'POST': 
                
                $this->newGroup();
                break;
            case 'PUT':

                $this->methodNotSupport();
                break;
            case 'DELETE': 

                $this->deleteGroupPermission();
                break;
            default:

                $this->methodNotSupport();    
        }
    }

    public function listGroup() {

        echo "group is listed";
    }

   /**
    * Create new Group Permission
    *
    * @var String $_POST['token']
    * @var String $_POST['name']
    * @var String $_POST['permission'] 
    */ 
    public function newGroup() {

        if ($this->http->method() != 'POST') {
            
            $this->tokenInvalid();  
            return;
        }
        
        if ($this->user->isTokenValid($_POST['token'])) {

                $this->model->load('account/group');

                if ($this->model->group->countGroup($_POST['name'])) {

                    $this->json->sendBack([
                        'success' => false,
                        'message' => 'name already exists'
                    ]); 

                    return;
                }

                $num_rows = $this->model->group->newGroup($_POST);
                $this->json->sendBack([
                    'success' => true,
                    'affected_rows' => $num_rows 
                ]);
        
                return;
            }

            $this->tokenInvalid();      
    }

    public function updateGroupPermission() {

        ## check method
        if ($this->user->isTokenValid($_PUT['token'])) {
                
            return;
        }

        $this->tokenInvalid();
    }

    Public function deleteGroupPermission() {

        echo 'Delete group permissions';
    }
    
    private function tokenInvalid() {
            
        $this->json->sendBack([
            'success' => false,
            'message' => 'Token is invalid or user has no permission'
        ]);
    }

    private function methodNotSupport() {
        
        $this->json->sendBack([
            'success' => false,
            'message' => 'Application does not support method ' . $this->http->method()
        ]);
    }
}