<?php 
// database class
class RoyalShorelineHotelModel {
    private $dbconn;

    public function __construct() {
        $this->dbconn = new PDO("mysql:host=localhost;dbname=royalshorelinehotel", "root", "");
        $this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    function register($Username, $Password, $Firstname, $Surname, $Phone, $Email) {
        
        $sql = "INSERT INTO register (Username, Password, Firstname, Surname, PhoneNumber, EmailAddress)
        VALUES (:username, :password, :firstname, :surname, :phone, :email)";

        // Password hash
        $hPassword = password_hash($Password, PASSWORD_DEFAULT);

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':username', $Username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hPassword, PDO::PARAM_STR);
        $stmt->bindParam(':firstname', $Firstname, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $Surname, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $Phone, PDO::PARAM_STR);
        $stmt->bindParam(':email', $Email, PDO::PARAM_STR);

        // execute statement
        $result = $stmt->execute();
        if($result == 1) {
            return true;
        } else {
            return false; // die
        }
    }

    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    function checkLogin($Username, $Password) {
        
        $sql = "SELECT * FROM register WHERE Username=:username ";

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':username', $Username, PDO::PARAM_STR);
        $stmt->execute();

        if($stmt->rowCount() > 0) {    
            $retVal = $stmt->fetch(PDO::FETCH_ASSOC);
            if(strlen($retVal['Password']) > 0) {
                if (password_verify($Password, $retVal['Password'])) {
                    return Array('reg_id'=>$retVal['RegisterID'],
                        'reg_username'=>$retVal['Username'],
                        'reg_firstname'=>$retVal['Firstname'],
                        'reg_surname'=>$retVal['Surname'],
                        'reg_phone'=>$retVal['PhoneNumber'],
                        'reg_email'=>$retVal['EmailAddress']);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
     //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
     function deleteAccount($registerID) {
        $sql = "DELETE FROM register WHERE RegisterID=:delrid";

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(":delrid", $registerID, PDO::PARAM_INT);

        // execute statement
        $result = $stmt->execute();
        if($result === true) {
            return true;
        } else {
            return false; // die
        }
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
     function showRooms() {
        $sql = "SELECT * FROM rooms";

        $rows = $stmt->fetchAll();

        // execute statement
        $result = $stmt->execute();
        if($result === true) {
            return true;
        } else {
            return false; // die
        }
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    function showBooking() {
        $sql = "SELECT * FROM bookings";

        $rows = $stmt->fetchAll();
        
        // execute statement
        $result = $stmt->execute();
        if($result === true) {
            return true;
        } else {
            return false; // die
        }
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    function addRoom($RoomType, $RoomPrice, $RoomDescription) {
        $sql = "INSERT INTO rooms (RoomType, RoomPrice, RoomDescription)
        VALUES (:roomtype, :roomprice, :roomdesc)";

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':roomtype', $RoomType, PDO::PARAM_STR);
        $stmt->bindParam(':roomprice', $RoomPrice, PDO::PARAM_STR);
        $stmt->bindParam(':roomdesc', $RoomDescription, PDO::PARAM_STR);

        // execute statement
        $result = $stmt->execute();
        if($result === true) {
            return true;
        } else {
            return false; // die
        }
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    function updateRoom($RoomID, $RoomType, $RoomPrice, $RoomDescription) {
        $sql = "UPDATE rooms SET RoomType=:roomtype, RoomPrice=:roomprice, RoomDescription=:roomdesc WHERE RoomID=:roomid";
        
        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':roomid', $RoomID, PDO::PARAM_INT);
        $stmt->bindParam(':roomtype', $RoomType, PDO::PARAM_STR);
        $stmt->bindParam(':roomprice', $RoomPrice, PDO::PARAM_STR);
        $stmt->bindParam(':roomdesc', $RoomDescription, PDO::PARAM_STR);

        // execute statement
        $result = $stmt->execute();
        if($result === true) { 
            return true;
        } else {
            return false; // die
        }
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    function deleteRoom($RoomID) {
        $sql = "DELETE FROM rooms WHERE RoomID=:roomid";

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(":roomid", $RoomID, PDO::PARAM_INT);

        // execute statement
        $result = $stmt->execute();
        if($result === true) {
            return true;
        } else {
            return false; // die
        }
    }

//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
function makeBooking($RegisterID, $RoomID, $RoomType, $BookingDate, $NumberOfAdult, $NumberOfChildren, $CheckInDate, $CheckOutDate) {
    $sql = "INSERT INTO bookings (RegisterID, RoomID, RoomType, BookingDate, NumberOfAdult, NumberOfChildren, CheckInDate, CheckOutDate)
    VALUES (:registerid, :roomid, :roomtype, :bookingdate, :numofadult, :numofchild, :checkindate, :checkoutdate)";

    // bind Param
    $stmt = $this->dbconn->prepare($sql);
    $stmt->bindParam(':registerid', $RegisterID, PDO::PARAM_INT);
    $stmt->bindParam(':roomid', $RoomID, PDO::PARAM_INT);
    $stmt->bindParam(':roomtype', $RoomType, PDO::PARAM_STR);
    $stmt->bindParam(':bookingdate', $BookingDate, PDO::PARAM_STR);
    $stmt->bindParam(':numofadult', $NumberOfAdult, PDO::PARAM_STR);
    $stmt->bindParam(':numofchild', $NumberOfChildren, PDO::PARAM_STR);
    $stmt->bindParam(':checkindate', $CheckInDate, PDO::PARAM_STR);
    $stmt->bindParam(':checkoutdate', $CheckOutDate, PDO::PARAM_STR);

    // execute statement
    $result = $stmt->execute();
    if($result === true) {
        return true;
    } else {
        return false; // die
    }
}

//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
function updateBooking($BookingID, $RegisterID, $RoomID, $RoomType, $BookingDate, $NumberOfAdult, $NumberOfChildren, $CheckInDate, $CheckOutDate) {
    $sql = "UPDATE bookings SET RegisterID=:registerid, RoomID=:roomid, RoomType=:roomtype, BookingDate=:bookingdate, NumberOfAdult=:numofadult, NumberOfChildren=:numofchild, CheckInDate=:checkindate, CheckOutDate=:checkoutdate WHERE BookingID=:bookingid";

    // bind Param
    $stmt = $this->dbconn->prepare($sql);
    $stmt->bindParam(':bookingid', $BookingID, PDO::PARAM_INT);
    $stmt->bindParam(':registerid', $RegisterID, PDO::PARAM_INT);
    $stmt->bindParam(':roomid', $RoomID, PDO::PARAM_INT);
    $stmt->bindParam(':roomtype', $RoomType, PDO::PARAM_STR);
    $stmt->bindParam(':bookingdate', $BookingDate, PDO::PARAM_STR);
    $stmt->bindParam(':numofadult', $NumberOfAdult, PDO::PARAM_STR);
    $stmt->bindParam(':numofchild', $NumberOfChildren, PDO::PARAM_STR);
    $stmt->bindParam(':checkindate', $CheckInDate, PDO::PARAM_STR);
    $stmt->bindParam(':checkoutdate', $CheckOutDate, PDO::PARAM_STR);

    // execute statement
    $result = $stmt->execute();
    if($result === true) {
        return true;
    } else {
        return false; // die
    }
}

//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
function deleteBooking($BookingID) {
    $sql = "DELETE FROM bookings WHERE BookingID=:bookingid";

    // bind Param
    $stmt = $this->dbconn->prepare($sql);
    $stmt->bindParam(":bookingid", $BookingID, PDO::PARAM_INT);

    // execute statement
    $result = $stmt->execute();
    if($result === true) {
        return true;
    } else {
        return false; // die
    }
  }
  //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  function registerLog($registerID, $url, $IPaddress, $Browser) {
      $sql = "INSERT INTO registerlog (RegisterID, URL, IPaddress, Browser)
      VALUES (:rid, :url, :ip, :br)";

      // bind Param
      $stmt = $this->dbconn->prepare($sql);
      $stmt->bindParam(":rid", $registerID, PDO::PARAM_INT);
      $stmt->bindParam(":url", $url, PDO::PARAM_STR);
      $stmt->bindParam(":ip", $IPaddress, PDO::PARAM_STR);
      $stmt->bindParam(":br", $Browser, PDO::PARAM_STR);

      // execute statement
      $result = $stmt->execute();
      if($result === true) {
          return true;
      } else {
          return false; // die
      }
  }
}
?>