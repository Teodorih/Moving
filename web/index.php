<?php
session_start();
$hash = session_id();
include_once("ajax.php");
include_once("../server_conf/conf.php"); // кофигурация БД
//$mysqli = connect_db();
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

        $global_id = TableValues::GetLastIdFromTable($mysqli);
        $error = false;
        $is_auth = false; // по умолчанию не авторизован
        $workspaceH = 500;
        if (isset($_POST['logoutbutton'])) {
            setcookie("hash", "", 1);
            session_destroy();
            header("Location:/ ");


        }
        if (isset($_POST['logbutton'])) {
            if ($_POST['login'] == "")
                $error = "Вы не ввели имя, введите его в поле ниже";
            else {
                $is_auth = true;

                $username = $_POST['login'];
                $CurrentUser = new IdAndName($global_id,$username);
                $NameExist = $CurrentUser->NameExist($mysqli);
                if($NameExist==true)
                {
                    $global_id = $CurrentUser->GetRealUserId($mysqli);
                } else {
                    $CurrentUser->AddNewUserToBD($mysqli);
                }
                $CurrentUser ->UpdateUserHash($mysqli,$hash);
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
        //$result->num_rows;
        $row = $result->fetch_assoc();
        $globeX = $row["coord_x"];
        $globeY = $row["coord_y"];

        $result = $mysqli->query("SELECT user_id FROM users");
        $i = ($result->num_rows);

        $result = $mysqli->query("SELECT DISTINCT users.user_id, coord_x,coord_y FROM users INNER JOIN coordinates ON users.user_id = coordinates.user_id WHERE users.user_id !='{$global_id}'");

        if (mysqli_num_rows($result) > 0) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $coord_array[$i] = array(x => $row["coord_x"], y => $row["coord_y"]);
                $i++;
            }
        }
        include_once("../views/view.php");
        break;
    case "ajax.php":
        $NewOwnCoordinates =new TableValues($_POST['ip'],$_POST['X'],$_POST['Y'],$_POST['name']);
        $NewOwnCoordinates->AddNewCoordinates($mysqli);
        break;
    case "response.php":
        TableValues::ReadTableValuesAndSendIt($mysqli);
        break;

}

?>

