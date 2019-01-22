<?php
use System\Model\Controller;
use TheSeer\Tokenizer\Exception;

/**
 * Group Permissions
 * 
 * @param GET  api = newGroupPermissions api=post/account/group-permission
 * @param GET  action
 * @param GET  token 
 */
class GroupPermissionController extends Controller
{

  /**
   * Logic hanlder
   * 
   * @param METHOD  'GET' | 'POST' | 'PUT' | 'DELETE'
   * @param String  $action 'permission' | list | create | update  | addUserToGroup
   */
  public function index()
  {

    $payload = $this->http->data();

    switch ($this->http->method()) {

      case 'GET':

        #region 
        $action = $this->http->data()['GET']['action'];
        switch ($action) {
          case 'permission':
            $this->listPermissions();
            break;
          case 'list':
            $this->listGroups();
            break;
        }
        #endregion

      case 'POST':

        switch ($action = $payload['GET']['action']) {
          case 'create':
            $this->newGroupPermissions();
            break;
          case 'update':
            $this->updateGroupPermission();
            break;
          case 'addUserToGroup':
            $this->addUserToGroup();
            break;
        }

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

  /**
   * list groups of permissions associate to a user
   * 
   * @param GET   action 'list'
   * @param GET   id  
   */
  public function listGroups()
  {
    $this->model->load('account/group');

    try {
      $data = $this->http->data()['GET'];

      #region
      if ($this->user->isTokenValid($data['token'])) 
      {
        $list_groups = $this->model->group->listGroups($data['id']); 

        return $this->json->sendBack([
          'success' => True,
          'data'    => $list_groups
        ]);
      }
  
    } catch(Exception $e) {
      $this->json->sendBack([
        'success' => false,
        'message' => 'There unexpected error happened, please contact administrator'
      ]);
    }
   
    $this->json->sendBack([
      'success' => false,
      'message' => 'Please check your token permissions'
    ]);
  }

  /**
   * Query permissions based on Group ID
   * 
   * @param GET action 'permission'
   * @param GET token
   * @param GET api     post/account/group-permission/
   * @param GET id      group id
   */
  public function listPermissions()
  {

    $this->model->load('account/group');
    
    #region 
    $getPaylod = $this->http->data()['GET'];
    if ($this->user->isTokenValid($getPaylod['token'])) {

      $permissions = $this->model->group->listPermissions($getPaylod['id']);
      $this->json->sendBack([
        'success' => true,
        'data' => json_decode($permissions)
      ]);

      return;
    }
    #endregion

    $this->json->sendBack([
      'success' => false,
      'message' => 'Please check your token validation & permissions'
    ]);
  }

  /**
   * Create new Group Permission
   *
   * @var String $_POST['token']
   * @var String $_POST['name']
   * @var String $_POST['permission'] 
   */
  public function newGroupPermissions()
  {
    ## Check method http
    if ($this->http->method() != 'POST') {

      $this->tokenInvalid();
      return;
    }

    if ($this->user->isTokenValid($_POST['token'])) {

      $this->model->load('account/group');
      
      ## Check existence
      if ($this->model->group->countGroup($_POST['name'])) {

        $this->json->sendBack([
          'success' => false,
          'message' => 'name already exists'
        ]);

        return;
      }

      ## Create new 
      $num_rows = $this->model->group->newGroup($_POST);
      $this->json->sendBack([
        'success' => true,
        'affected_rows' => $num_rows
      ]);

      return;
    }

    $this->tokenInvalid();
  }

  /**
   * Add user to a permission group
   */
  public function addUserToGroup()
  {
    ## Check method http
    if ($this->http->method() != 'POST') {
      $this->json->sendBack([
        'success' => fasle,
        'message' => 'Unsupported method for this api'
      ]);

      return;
    }

    ## if token is valid, process
    if ($this->user->isTokenValid($this->http->data()['GET']['token'])) {

      $this->model->load('account/group');

      if ($this->model->group->addUserToGroup($this->http->data()['POST'])) {
        $this->json->sendBack([
          'success' => true,
          'message' => 'Group has been added a permission'
        ]);

        return;
      }

      ## else return errors
      $this->json->sendBack([
        'success' => Fasle,
        'message' => 'Error: Please check if Group Exists or UserId Exists'
      ]);
    }
  }

  public function deleteGroupPermission()
  {

    echo 'Delete group permissions';
  }

  private function tokenInvalid()
  {

    $this->json->sendBack([
      'success' => false,
      'message' => 'Token is invalid or user has no permission'
    ]);
  }

  private function methodNotSupport()
  {

    $this->json->sendBack([
      'success' => false,
      'message' => 'Application does not support method ' . $this->http->method()
    ]);
  }
}