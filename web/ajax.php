<?php
class IdAndName{
    public $id;
    public $username;
    public function __construct($id, $username) {
        $this->id = $id;
        $this->username = $username;
    }
    public function AddNewUserToBD($mysqli)
    {
        $query = "INSERT INTO `users`(user_id,user_name) VALUES ('{$this->id}','{$this->username}')";
        mysqli_query($mysqli, $query);
    }
    public function GetRealUserId($mysqli)
    {
        $result = $mysqli->query("SELECT user_id FROM users WHERE user_name='{$this->username}'  ORDER BY user_id  DESC  LIMIT 1 ");
        $row = $result->fetch_assoc();
        $id = $row["user_id"];
        return $id;
    }
    public function NameExist($mysqli)
    {
        $result = $mysqli->query("SELECT * FROM users WHERE user_name='{$this->username}'");
        if ($result->num_rows == 0)
            return false;
        else
            return true;
    }
    public function UpdateUserHash($mysqli,$hash)
    {
        $query = "UPDATE users SET user_hash='{$hash}' WHERE user_name='{$this->username}'";
        mysqli_query($mysqli, $query);
        setcookie("hash", $hash, time() + 60 * 60 * 24 * 30);
    }
}

class TableValues extends IdAndName{
    public $id;
    public $x;
    public $y;
    public $username;
    public function __construct($id ,$x, $y, $username) {
        $this->id = $id;
        $this->x = $x;
        $this->y = $y;
        $this->username = $username;
    }
    public static function ReadTableValuesAndSendIt($mysqli)
    {
        $result = $mysqli->query("SELECT * FROM users INNER JOIN coordinates ON users.user_id=coordinates.user_id ");
        while ($row = $result->fetch_assoc()) {
            if ($row[user_id] == $_POST['conf_ip']) {
                if ($_POST['moving']=='false' || $_POST['moving']==null)
                {
                    $db_array[] = array(X => $row["coord_x"], Y => $row["coord_y"], Name => "Mine");
                }
            } else {
                $db_array[] = array(X => $row["coord_x"], Y => $row["coord_y"], Name => $row['user_name']);
            }
        }

        echo json_encode($db_array);
    }

    public static function GetLastIdFromTable($mysqli)
    {
        $result = $mysqli->query("SELECT user_id FROM users  ORDER BY user_id  DESC  LIMIT 1 ");
        if ($result->num_rows == 0)
        {
            $id = 1;
        }
        else {
            $row = $result->fetch_assoc();
            $id = $row["user_id"] + 1;
        }
        return $id;
    }

    function AddNewCoordinates($mysqli)
    {
        $query = "UPDATE users SET user_name='$this->username' WHERE user_id='$this->id'";
        mysqli_query($mysqli, $query);
        $query = "DELETE FROM `coordinates` WHERE user_id='{$this->id}'";
        mysqli_query($mysqli, $query);
        $query = "INSERT INTO `coordinates`(user_id,coord_x,coord_y) VALUES ('{$this->id}','{$this->x}','$this->y')";
        mysqli_query($mysqli, $query);
    }
}