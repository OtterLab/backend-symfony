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
            'Password'=>$res['reg_password'],
            'Firstname'=>$res['reg_firstname'],
            'Surname'=>$res['reg_surname'],
            'PhoneNumber'=>$res['reg_phone'],
            'EmailAddress'=>$res['reg_email'],
            'Hash'=>$this->user_token);
        } elseif(count($res) == 1) {
            $this->reg_id = $res['RegisterID'];
            $this->user_token = md5(json_encode($res));
            return Array('Hash'=>$this->user_token);
        } 
    }

    // Register
    public function register($Username, $Password, $Firstname, $Surname, $Phone, $Email) {
        global $RoyalShorelineHotelDB;
            if($RoyalShorelineHotelDB->register($Username, $Password, $Firstname, $Surname, $Phone, $Email)) {
                return true;
            } else {
                return 0;
            }
    }

     // Delete Account
     public function deleteAccount($registerID) {
        global $RoyalShorelineHotelDB;
        if($RoyalShorelineHotelDB->deleteAccount($registerID)) {
            return true;
        } else {
            return 0;
        }
    }

    // Logout
    public function logout() {
        $session->invalidate();
    }

    // Add Room
    public function addRoom($RoomImage, $RoomType, $RoomPrice, $RoomDescription) {
        global $RoyalShorelineHotelDB;
            if($RoyalShorelineHotelDB->addRoom($RoomImage, $RoomType, $RoomPrice, $RoomDescription)) {
                return true;
            } else {
                return 0;
            }
    }

    // Update Room
    public function updateRoom($RoomID, $RoomImage, $RoomType, $RoomPrice, $RoomDescription) {
        global $RoyalShorelineHotelDB;
            if($RoyalShorelineHotelDB->updateRoom($RoomID, $RoomImage, $RoomType, $RoomPrice, $RoomDescription)) {
                return true;
            } else {
                return 0;
            }
    }

    // Delete Room
    public function deleteRoom($RoomID) {
        global $RoyalShorelineHotelDB;
        if($RoyalShorelineHotelDB->deleteRoom($RoomID)) {
            return true;
        } else {
            return 0;
        }
    }

    // Make Booking
    public function makeBooking($RegisterID, $RoomID, $RoomImage, $RoomType, $BookingDate, $NumberOfAdult, $NumberOfChildren, $CheckInDate, $CheckOutDate) {
        global $RoyalShorelineHotelDB;
            if($RoyalShorelineHotelDB->makeBooking($RegisterID, $RoomID, $RoomImage, $RoomType, $BookingDate, $NumberOfAdult, $NumberOfChildren, $CheckInDate, $CheckOutDate)) {
                return true;
            } else {
                return 0;
            }
    }

    // Update Booking
    public function updateBooking($BookingID, $RegisterID, $RoomID,  $RoomImage, $RoomType, $BookingDate, $NumberOfAdult, $NumberOfChildren, $CheckInDate, $CheckOutDate) {
        global $RoyalShorelineHotelDB;
            if($RoyalShorelineHotelDB->updateBooking($BookingID, $RegisterID, $RoomID,  $RoomImage, $RoomType, $BookingDate, $NumberOfAdult, $NumberOfChildren, $CheckInDate, $CheckOutDate)) {
                return true;
            } else {
                return 0;
            }
    }

    // Delete Booking
    public function deleteBooking($BookingID) {
        global $RoyalShorelineHotelDB;
        if($RoyalShorelineHotelDB->deleteBooking($BookingID)) {
            return true;
        } else {
            return 0;
        }
    }
}
?>