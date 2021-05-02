<?php 
// database class
class RoyalShorelineHotelModel {
    private $dbconn;

    public function __construct() {
        $this->dbconn = new PDO("mysql:host=localhost;dbname=testtable", "root", "");
        $this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    function register($Username, $Password, $Firstname, $Surname, $Phone, $Email) {
        
        $sql = "INSERT INTO register (Username, Password, Firstname, Surname, PhoneNumber, EmailAddress)
        VALUES (:uname, :upass, :rfirstname, :rsurname, :rphone, :remail)";

        // Password hash
        $hPassword = password_hash($Password, PASSWORD_DEFAULT);

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':uname', $Username, PDO::PARAM_STR);
        $stmt->bindParam(':upass', $hPassword, PDO::PARAM_STR);
        $stmt->bindParam(':rfirstname', $Firstname, PDO::PARAM_STR);
        $stmt->bindParam(':rsurname', $Surname, PDO::PARAM_STR);
        $stmt->bindParam(':rphone', $Phone, PDO::PARAM_STR);
        $stmt->bindParam(':remail', $Email, PDO::PARAM_STR);

        // execute statement
        $result = $stmt->execute();
        if($result === true) {
            return true;
        } else {
            return false; // die
        }
    }

    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    function checkLogin($Username, $Password) {
        
        $sql = "SELECT * FROM register WHERE Username=:uname ";

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':uname', $Username, PDO::PARAM_STR);

        $stmt->execute();
        
        if($stmt->rowCount() > 0) {    
            $retVal = $stmt->fetch(PDO::FETCH_ASSOC);
            if(strlen($retVal['Password']) > 0) {
                if (password_verify($Password, $retVal['Password'])) {
                    return Array('reg_id'=>$retVal['RegisterID'],
                        'reg_username'=>$retVal['Username'],
                        'reg_password'=>$retVal['Password'],
                        'reg_firstname'=>$retVal['Firstname'],
                        'reg_surname'=>$retVal['Surname'],
                        'reg_phone'=>$retVal['PhoneNumber'],
                        'reg_email'=>$retVal['EmailAddress']);
                        
                } else {
                    return false;
                }
            } else {
                return Array('reg_id'=>$retVal['RegisterID']);
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
     function displayRoomLists() {
        $sql = "SELECT * FROM rooms";

        // execute statement
        $result = $stmt->execute();
        if($result === true) {
            return true;
        } else {
            return false; // die
        }
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    function addRoom($RoomImage, $RoomType, $RoomPrice, $RoomDescription) {
        $sql = "INSERT INTO rooms (RoomImage, RoomType, RoomPrice, RoomDescription)
        VALUES (:rmimg, :rmtype, :rmprice, :rmdescript)";

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':rmimg', $RoomImage, PDO::PARAM_STR);
        $stmt->bindParam(':rmtype', $RoomType, PDO::PARAM_STR);
        $stmt->bindParam(':rmprice', $RoomPrice, PDO::PARAM_STR);
        $stmt->bindParam(':rmdescript', $RoomDescription, PDO::PARAM_STR);

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
        $sql = "UPDATE rooms SET RoomType=:rmtype, RoomPrice=:rmprice, RoomDescription=:rmdescript WHERE RoomID=:roomid";

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':roomid', $RoomID, PDO::PARAM_INT);
        $stmt->bindParam(':rmtype', $RoomType, PDO::PARAM_STR);
        $stmt->bindParam(':rmprice', $RoomPrice, PDO::PARAM_STR);
        $stmt->bindParam(':rmdescript', $RoomDescription, PDO::PARAM_STR);

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
function showBookingLists() {
    $sql = "SELECT * FROM bookings";

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
    VALUES (:rid, :rmid, :rmtype, :bookdate, :numofadult, :numofchild, :ckindate, :ckoutdate)";

    // bind Param
    $stmt = $this->dbconn->prepare($sql);
    $stmt->bindParam(':rid', $RegisterID, PDO::PARAM_INT);
    $stmt->bindParam(':rmid', $RoomID, PDO::PARAM_INT);
    $stmt->bindParam(':rmtype', $RoomType, PDO::PARAM_STR);
    $stmt->bindParam(':bookdate', $BookingDate, PDO::PARAM_STR);
    $stmt->bindParam(':numofadult', $NumberOfAdult, PDO::PARAM_STR);
    $stmt->bindParam(':numofchild', $NumberOfChildren, PDO::PARAM_STR);
    $stmt->bindParam(':ckindate', $CheckInDate, PDO::PARAM_STR);
    $stmt->bindParam(':ckoutdate', $CheckOutDate, PDO::PARAM_STR);

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
    $sql = "UPDATE bookings SET RegisterID=:rid, RoomID=:rmid, RoomType=:rmtype, BookingDate=:bookdate, NumberOfAdult=:numofadult, NumberOfChildren=:numofchild, CheckInDate=:ckindate, CheckOutDate=:ckoutdate WHERE BookingID=:bookid";

    // bind Param
    $stmt = $this->dbconn->prepare($sql);
    $stmt->bindParam(':bookid', $BookingID, PDO::PARAM_INT);
    $stmt->bindParam(':rid', $RegisterID, PDO::PARAM_INT);
    $stmt->bindParam(':rmid', $RoomID, PDO::PARAM_INT);
    $stmt->bindParam(':rmtype', $RoomType, PDO::PARAM_STR);
    $stmt->bindParam(':bookdate', $BookingDate, PDO::PARAM_STR);
    $stmt->bindParam(':numofadult', $NumberOfAdult, PDO::PARAM_STR);
    $stmt->bindParam(':numofchild', $NumberOfChildren, PDO::PARAM_STR);
    $stmt->bindParam(':ckindate', $CheckInDate, PDO::PARAM_STR);
    $stmt->bindParam(':ckoutdate', $CheckOutDate, PDO::PARAM_STR);

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