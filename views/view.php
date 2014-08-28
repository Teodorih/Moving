<html>
<head>
    <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">

    <title>Тестовое задание</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>
    <script type="text/javascript" src="dirMoving.js"></script>
    <script type="text/javascript">

        var id = "<?php echo $global_id;?>";

    </script>
    <script type="text/javascript" src="jquery-ui.js"></script>
    <script type="text/javascript" src="jquery-ui.min.js"></script>
    <link href="style.css" rel="stylesheet" type="text/css"/>


</head>
<body>
<header>
<div id="header">
     <img src="icons/logo.png" alt="ошибка" width=80px height=80px>
    You can try to move it
    <img id="db_icon" src="icons/database256.gif" alt="ошибка"  width=80px height=80px>
    <a href="#" id="dbButton">Открыть БД</a>
</div>
</header>
<div id="content">
    <center>

        <?php if ($is_auth) {
        echo "Приветствуем вас, " . $username;
        ?>
        <form action="index.php" method="post">
            <input name="logoutbutton" type="submit" value="Выход">
        </form>
        <br><br>


        <div id="sq" left='50%'
             style='position:absolute;
                 margin-left:30% ;
                 background-color:#ffffff;
                 background-color:cornflowerblue;
                 width:<?php echo $workspaceH ?>px;
                 height:<?php echo $workspaceH ?>px;
                 margin-top:10px ;
                 border: 10px;'>


            <?php
            for ($i = 0;
            $i < sizeof($coord_array);
            $i++) {

            ?>
            <div id="<?php echo "square" . $i ?>"
                 style="position:inherit;top: <?php echo $coord_array[$i][x] ?>  ; left: <?php echo $coord_array[$i][y] ?>; background-color:black; width:50px; height:50px;">
        </div>

    <?php
    }
    ?>
        <div id="draggble"
             style="position:inherit;top: <?php echo $globeX ?>; left:<?php echo $globeY ?>; background-color:aqua; width:50px; height:50px;">
        </div>

</div>
<?php

} else {
    echo $error;
    ?>
    <div id="autorization">
        <div id="advice">Введите имя</div>
        <form action="index.php" method="post">
            <input id="name_input" type="text" name="login" onfocus="advice.style.visibility = 'hidden'"
                   onblur="advice.style.visibility = 'visible'">

            <input name="logbutton" type="submit" value="Войти">
        </form>
    </div>
<?php
}

?>

</center>
</div>

</body>
</html>