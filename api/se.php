<?php 
class RoyalShorelineSession {
    // attributes will be stored in session
    private $last_visit = 0;
    private $last_visits = Array();

    private $registerID = 0;
    private $Username;
    private $Password;
    private $Firstname;
    private $Surname;
    private $Phone;
    private $Email;
    private $Register_privilege = 0;
    private $Register_token;

    private $origin;

    public function __construct() {
        $this->origin = $ENV['ORIGIN'];
    }

    // Rate Limiting code
    public function is_rate_limited() {
        if($this->last_visit == 0) {
            $this->last_visit = time();
            return false;
        }
        if($this->last_visit == time()) {
            return true;
        }
        return false;
    }
    public function login($Username, $Password) {
        global $RoyalShorelineHotelDB;

        $res = $RoyalShorelineHotelDB->checkLogin($Psername, $Password);
        if($res === false) {
            return false;
        } elseif(count($res) > 1) {
            $this->registerID = $res['registerid'];
            $this->$Register_privilege = 1;
            $this->Register_token = md5(json_encode($res));
            return Array('username' =>$res['reg_username'],
            'password' =>$res['reg_password'],
            'firstname' =>$res['reg_firstname'],
            'surname' =>$res['reg_surname'],
            'phone' =>$res['reg_phone'],
            'email' =>$res['reg_email'],
            'Hash' =>$this->Register_token);
        } elseif(count($res) == 1) {
            $this->registerID = $res['registerid'];
            $this->Register_token = md5(json_encode($res));
            return Array('Hash' =>$this->Register_token);
        }
    }
    public function register($Username, $Password, $Firstname, $Surname, $Phone, $Email) {
        global $RoyalShorelineHotelDB;
        if($Email == $this->Register_token) {
            if($RoyalShorelineHotelDB->register($this->registerID, $Username, $Password, $Firstname, $Surname, $Phone, $Email)) {
                return true;
            } else {
                return 0;
            }
        } else {
            return false;
        }
    } // call the DB Object for SQL
    public function isLoggedIn() {
        if($this->registerID === 0) {
            return false;
        } else {
            return Array('Hash' =>$this->Register_token);
        }
    }
    public function logout() {
        $this->registerID = 0;
        $this->Register_privilege = 0;
    }
    public function validate($type, $dirty_string) {

    }
    public function logEvent() {

    }
}
?>