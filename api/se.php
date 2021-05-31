<?php 
class RoyalShorelineSession {
    // attributes will be stored in session
    private $last_visit = 0;
    private $last_visits = Array();

    private $reg_id = 0;
    private $reg_username;
    private $reg_password;
    private $reg_firstname;
    private $reg_surname;
    private $reg_phone;
    private $reg_email;
    private $reg_accessrights;
    private $user_token;

    // Rate Limiting
    public function is_rate_limited() {
        date_default_timezone_set("Australia/Brisbane");
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
    public function Login($Username, $Password) {
        global $RoyalShorelineHotelDB;

        $res = $RoyalShorelineHotelDB->checkLogin($Username, $Password);
        if($res === false) {
            return false;
        } elseif(count($res) > 1) {
            $this->reg_id = $res['reg_id'];
            $this->user_token = md5(json_encode($res));
            return Array('Username'=>$res['reg_username'],
            'Firstname'=>$res['reg_firstname'],
            'Surname'=>$res['reg_surname'],
            'PhoneNumber'=>$res['reg_phone'],
            'EmailAddress'=>$res['reg_email'],
            'accessRights'=>$res['reg_accessrights'],
            'Hash'=>$this->user_token);
        }
    }

    // Register
    public function register($Username, $Password, $Firstname, $Surname, $Phone, $Email, $AccessRights) {
        global $RoyalShorelineHotelDB;
            if($RoyalShorelineHotelDB->register($Username, $Password, $Firstname, $Surname, $Phone, $Email, $AccessRights)) {
                return true;
            } else {
                return false;
            }
    }

     // Delete Account
     public function deleteAccount($registerID) {
        global $RoyalShorelineHotelDB;
        if($RoyalShorelineHotelDB->deleteAccount($registerID)) {
            return true;
        } else {
            return false;
        }
    }

    // Display Register
    public function displayRegister() {
        global $RoyalShorelineHotelDB;
        $result = $RoyalShorelineHotelDB->displayRegister();
        if(count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Logout
    public function logout() {
        $reg_id = 0;
        session_unset();
        session_destroy();
        //$session->invalidate();
    }

    // Add Room
    public function addRoom($RoomType, $RoomPrice, $RoomDescription) {
        global $RoyalShorelineHotelDB;
            if($RoyalShorelineHotelDB->addRoom($RoomType, $RoomPrice, $RoomDescription)) {
                return true;
            } else {
                return false;
            }
    }

    // Show Room
    public function showRooms() {
        global $RoyalShorelineHotelDB;
        $result = $RoyalShorelineHotelDB->showRooms();
        if(count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Show Booking
    public function showBooking() {
        global $RoyalShorelineHotelDB;
        $result = $RoyalShorelineHotelDB->showBooking();
        if(count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Update Room
    public function updateRoom($RoomID, $RoomType, $RoomPrice, $RoomDescription) {
        global $RoyalShorelineHotelDB;
            if($RoyalShorelineHotelDB->updateRoom($RoomID, $RoomType, $RoomPrice, $RoomDescription)) {
                return true;
            } else {
                return false;
            }
    }

    // Delete Room
    public function deleteRoom($RoomID) {
        global $RoyalShorelineHotelDB;
        if($RoyalShorelineHotelDB->deleteRoom($RoomID)) {
            return true;
        } else {
            return false;
        }
    }

    // Make Booking
    public function makeBooking($RegisterID, $RoomID, $RoomType, $BookingDate, $NumberOfAdult, $NumberOfChildren, $CheckInDate, $CheckOutDate) {
        global $RoyalShorelineHotelDB;
            if($RoyalShorelineHotelDB->makeBooking($RegisterID, $RoomID, $RoomType, $BookingDate, $NumberOfAdult, $NumberOfChildren, $CheckInDate, $CheckOutDate)) {
                return true;
            } else {
                return false;
            }
    }

    // Update Booking
    public function updateBooking($BookingID, $RegisterID, $RoomID, $RoomType, $BookingDate, $NumberOfAdult, $NumberOfChildren, $CheckInDate, $CheckOutDate) {
        global $RoyalShorelineHotelDB;
            if($RoyalShorelineHotelDB->updateBooking($BookingID, $RegisterID, $RoomID, $RoomType, $BookingDate, $NumberOfAdult, $NumberOfChildren, $CheckInDate, $CheckOutDate)) {
                return true;
            } else {
                return false;
            }
    }

    // Delete Booking
    public function deleteBooking($BookingID) {
        global $RoyalShorelineHotelDB;
        if($RoyalShorelineHotelDB->deleteBooking($BookingID)) {
            return true;
        } else {
            return false;
        }
    }
}
?>