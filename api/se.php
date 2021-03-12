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
    private $user_privilege = 0;
    private $user_token;

    // Rate Limiting
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

    // Login
    public function login($Username, $Password) {
        global $RoyalShorelineHotelDB;

        $res = $RoyalShorelineHotelDB->checkLogin($Username, $Password);
        if($res === false) {
            return false;
        } elseif(count($res) > 1) {
            $this->registerID = $res['rid'];
            $this->user_privilege = 1;
            $this->user_token = md5(json_encode($res));
            return Array('Username'=>$res['uname'],
            'Password'=>$res['upass'],
            'Firstname'=>$res['rfirstname'],
            'Surname'=>$res['rsurname'],
            'Phone'=>$res['rphone'],
            'Email'=>$res['remail'],
            'Hash'=>$this->user_token);
        } elseif(count($res) == 1) {
            $this->registerID = $res['rid'];
            $this->user_token = md5(json_encode($res));
            return Array('Hash'=>$this->user_token);
        }
    }

    // Register
    public function register($Username, $Password, $Firstname, $Surname, $Phone, $Email) {
        global $RoyalShorelineHotelDB;
        if($Email == $this->user_token) {
            if($RoyalShorelineHotelDB->register($this->registerID, $Username, $Password, $Firstname, $Surname, $Phone, $Email)) {
                return true;
            } else {
                return 0;
            }
        } else {
            return false;
        }
    }

    // is Logged In
    public function isLoggedIn() {
        if($this->registerID === 0) {
            return false;
        } else {
            return Array('Hash'=>$this->user_token);
        }
    }

    // Logout
    public function logout() {
        $this->registerID = 0;
        $this->user_privilege = 0;
    }
}
?>