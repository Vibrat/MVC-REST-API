<?php
namespace Authenticator;

/**
 * Authenticator class for login
 *
 * Design by Lam Nguyen
 */
class Authenticator {
    /**
     * @var String $username
     * @var String $password
     * @var Bool $isLogged
     */
    private $username;
    private $password;
    public  $isLogged = false;
    /**
     * @var \Token\Token 
     */
    public  $tokener;
    
    /** @var \MySqliDatabase $db */
    private $db;

    function __construct(
        \MySqliDatabase $db, 
        \Token\Token $tokener) 
        {

        /** assign database connection for future use */
        $this->db = $db;

        ## save tokener into Authenticator
        if ($tokener instanceof \Token\Token) {
            $this->tokener = $tokener;
            $this->isLogged = ($this->tokener->token ? true : false) ;
        }
    }

    /**
     * Check if user already logged
     * 
     * @param  String $token
     * @return Bool
     */
    public function isLogged($token) {
        return $this->isLogged || $this->tokener->checkToken($token);
    }

    /**
     * get Token
     */
    public function getToken() {
        return $this->tokener->getToken();
    }

    /**
     * Login Authentication
     * 
     * @param Array $credentials ['username'=> 'lam-nguyen', 'password' => '12312731562' ]
     */
    public function login(Array $credentials) {
        
        ## call database's credentials
        $sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "users` WHERE username = '" . $credentials['username'] . "' AND password = '" . $credentials['password'] . "'";

        /** @var Reponse Connection */
        $query = $this->db->query($sql);

        if (!empty($query->row('total'))) {
            return $this->tokener->createToken();
        }   

        return false;
    }
}