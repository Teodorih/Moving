<?php
session_start();
$mysqli = connect_db();
$code_flag = "index.php";
$need_reload = true;
if (isset($_POST['X'])) {
    $code_flag = "ajax.php";
}
if (isset($_POST['conf'])) {
    $code_flag = "response.php";
}
switch ($code_flag) {
    case "index.php":
        //проверка индексации
        $result = $mysqli->query("SELECT user_id FROM users  ORDER BY user_id  DESC  LIMIT 1 ");
        if ($result->num_rows == 0)
        {
            $global_id = 1;
        }
        else {
            while ($row = $result->fetch_assoc()) {
                $global_id = $row["user_id"] + 1;
            }
        }
        $error = false;
        $is_auth = false; // по умолчанию не авторизован
        $workspaceH = 500;
        if (isset($_POST['logoutbutton'])) {
            setcookie("hash", "", 1);
            session_destroy();
            header("Location: /index.php");


        }
        if (isset($_POST['logbutton'])) {
            if ($_POST['login'] == "")
                $error = "Вы не ввели имя, введите его в поле ниже";
            else {
                $is_auth = true;

                $username = $_POST['login'];

                $result = $mysqli->query("SELECT * FROM users WHERE user_name='{$username}'");

                if ($result->num_rows == 0) {
                    $query = "INSERT INTO `users`(user_id,user_name) VALUES ('{$global_id}','{$username}')";
                    mysqli_query($mysqli, $query);
                } else {
                    $result = $mysqli->query("SELECT user_id FROM users WHERE user_name='{$username}'  ORDER BY user_id  DESC  LIMIT 1 ");
                    while ($row = $result->fetch_assoc()) {
                        $global_id = $row["user_id"];
                    }
                }
                $hash = md5(generateCode(10));
                $query = "UPDATE users SET user_hash='{$hash}' WHERE user_name='{$username}'";
                mysqli_query($mysqli, $query);
                setcookie("hash", $hash, time() + 60 * 60 * 24 * 30);
            }
        } else {
            if ((empty($_COOKIE['hash']))) {
            } else {
                $is_auth = true;
                $result = $mysqli->query("SELECT * FROM users WHERE user_hash='{$_COOKIE['hash']}'");
                if (($result->num_rows)==0)
                {
                    $is_auth=false;// костыль(случай- куки есть, но нет ни 1 совпадения по hash)
                }
                while ($row = $result->fetch_assoc()) {
                    $username = $row["user_name"];
                    $global_id = $row["user_id"];
                }

            }
        }
        ?>
        <script type="text/javascript"> var username = "<?php echo $username;?>"; </script>
        <?php

        $result = $mysqli->query("SELECT * FROM coordinates WHERE user_id='{$global_id}'  ORDER BY id  DESC  LIMIT 1 ");
        $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $globeX = $row["coord_x"];
            $globeY = $row["coord_y"];

        }


        $result = $mysqli->query("SELECT user_id FROM users");
        $i = ($result->num_rows);
        //Записываем все id в массив, кроме нашего.
        while ($row = $result->fetch_assoc()) {
            if ($row["user_id"] == $global_id) {
            } else {
                $array_ses[($result->num_rows) - $i] = $row["user_id"];
                $i--;
            }
        }


        $result = $mysqli->query("SELECT DISTINCT users.user_id, coord_x,coord_y FROM users INNER JOIN coordinates ON users.user_id = coordinates.user_id WHERE users.user_id !='{$global_id}'");

        if (mysqli_num_rows($result) > 0) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $coord_array[$i] = array(x => $row["coord_x"], y => $row["coord_y"]);
                $i++;
            }
        }
        //необходимо проверить, нужен ли массив array_ses!!!
        include_once("../views/view.php");
        break;
    case "ajax.php":
        $id = $_POST['ip'];
        $x = $_POST['X'];
        $y = $_POST['Y'];
        $username = $_POST['name'];

        $query = "UPDATE users SET user_name='$username' WHERE user_id='$id'";
        mysqli_query($mysqli, $query);
        $query = "DELETE FROM `coordinates` WHERE user_id='{$id}'";
        mysqli_query($mysqli, $query);
        $query = "INSERT INTO `coordinates`(user_id,coord_x,coord_y) VALUES ('{$id}','{$x}','$y')";
        mysqli_query($mysqli, $query);
        break;
    case "response.php":
        $result = $mysqli->query("SELECT * FROM users INNER JOIN coordinates ON users.user_id=coordinates.user_id ");
        while ($row = $result->fetch_assoc()) {
            if ($row[user_id] == $_POST['conf_ip']) {
                if ($_POST['moving']==false)
                {
                    $db_array[] = array(X => $row["coord_x"], Y => $row["coord_y"], Name => "Mine");
                }
            } else {
                $db_array[] = array(X => $row["coord_x"], Y => $row["coord_y"], Name => $row['user_name']);
            }
        }

        echo json_encode($db_array);

        break;

}

function connect_db()
{
    $mysqli = new mysqli("127.0.0.1", "root","04610461");
    mysqli_select_db($mysqli, "square_coordinates");
    if ($mysqli->connect_errno) {
        echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    return $mysqli;
}

function generateCode($length = 6)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0, $clen)];
    }
    return $code;
}

?>

