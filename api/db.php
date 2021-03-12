<?php 
// database class
class RoyalShorelineHotelModel {
    private $dbconn;

    public function __construct() {
        $this->dbconn = new PDO("mysql:host=localhost;dbname=testtable", "root", "");
        $this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Register
    function register($registerID, $Username, $Password, $Firstname, $Surname, $Phone, $Email) {
        $sql = "INSERT INTO register (RegisterID, Firstname, Suranme, PhoneNumber, EmailAddress)
        VALUES (:rid, :uname, :upass, :rfirstname, :rsurname, :rphone, :remail)";

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':rid', $registerID, PDO::PARAM_INT);
        $stmt->bindParam(':uname', $Username, PDO::PARAM_STR);
        $stmt->bindParam(':upass', $Password, PDO::PARAM_STR);
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

    // check Login
    function checkLogin($Username, $Password) {
        $sql = "SELECT * FROM login WHERE Username=:uname";

        // bind Param
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':uname', $Username, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $retVal = $stmt->fetch(PDO::FETCH_ASSOC);
            if(strlen($retVal['upass']) > 0) {
                if($retVal['upass'] == $Password) {
                    return Array('registerID'=>$retVal['rid'],
                    'Username'=>$retVal['uname'],
                    'Password'=>$retVal['upass'],
                    'Firstname'=>$retVal['rfirstname'],
                    'Surname'=>$retVal['rsurname'],
                    'Phone'=>$retVal['rphone'],
                    'Email'=>$retVal['remail']);
                } else {
                    return false;
                }
            } else {
                return Array('registerID'=>$retVal['rid']);
            }
        } else {
            return false;
        }
    }

    function userExists($Username) {
        $sql = "SELECT * FROM login WHERE Username = :uname";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':uname', $Username, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }

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
}
?>