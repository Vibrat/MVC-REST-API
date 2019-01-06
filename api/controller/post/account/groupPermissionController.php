<?php
/**
 * Group Permissions
 * 
 * @method newGroup api=post/account/group-permission/new-group
 */

use System\Model\Controller;

class GroupPermissionController extends Controller {

   /**
    * Function called when method not found 
    * 
    */ 
   public function index() {
       
       $this->json->sendBack([
           'success' => false,
           'message' => 'Can\' find method ' . $_GET['api']
       ]);

       return 4;
   }
}