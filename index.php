<?php
                require_once('config.php');
                
                $db_selected = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                
                if(!$db_selected) {
                    die('Could not connect: '. mysqli_connect_error());
                }
                
                $sql = "SELECT * FROM login";
                $checkval = false;
                
                if(!empty($_POST)){
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    
                    $results = mysqli_query($db_selected, $sql);
                    
                    if (!$results) {
                        die('Invalid query: ' . mysqli_connect_error());
                    }
                    
                    while($result = mysqli_fetch_array($results)) {
                        if(($result['username'] == $username || $result['email'] == $username) &&   $result['password'] == $password) {
                            header("LOCATION: cross_road.html");
                        }
                    }
                }
                mysqli_close($db_selected);
?>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <title>Orbit: Dynamic Library</title>
        <script src='movement.js'></script>

        <link rel="stylesheet" href="main2.css">
        <link rel="stylesheet" href="result2.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
            var result_width = 20;
            var start_show = 0;
            var num_show = 40;

            function reset_result_div_size(){
                var result_div = document.getElementById("result_field");
                result_div.style.height = window.innerHeight;
                result_div.style.width = window.innerWidth;

            }


            function show_results(){
                for(var i = 0; i < 40; i++){
                    var element = document.getElementById("result_field");
                    reset_result_div_size();
                    var space = document.createElement("div");
                    space.className = 'animate_object';
                    space.style.width = result_width;
                    space.style.height = result_width;
                    space.style.left = window.innerWidth;
                    space.style.borderRadius = 2;
                    element.appendChild(space);

                }
                my_move();
            }


            function my_move() {
                var elem = document.getElementsByClassName("animate_object");
                reset_result_div_size();
                var elem_pos = [];
                var elem_spd = [];
                var elem_not_passed = [];
                var spd_min = 2;
                var spd_max = 6;
                var width_c = window.innerWidth;
                var height_c = Math.round(window.innerHeight * (17 / 20));
                var len = elem.length;
                var ratio = 1;
                if(len > 1){
                    ratio = (len - 1) / len;
                }
                var check_len = Math.round((width_c - result_width * 2) * ratio);

                for(var i = 0; i < len; i++){
                    elem_spd[i] = Math.floor(Math.random() * (spd_max - spd_min)) + spd_min;
                    elem_pos[i] = width_c;
                    elem_not_passed[i] = true;
                    elem[i].style.top = Math.round(height_c * Math.random());
                    if(elem_spd[i] == 2){
                        elem[i].style.backgroundColor = "#ffff80";
                    } else{
                        elem[i].style.backgroundColor = "lightyellow";
                    }
                    elem[i].style.zIndex = elem_spd[i];
                }   

                var id = setInterval(frame, 17);
                var moving = 1;
                function frame() {
                    for(var i = 0; i < moving; i++){
                        if(elem_pos[i] <= -result_width){
                            elem[i].style.left = width_c;
                            elem_pos[i] = window.innerWidth;
                        }else{
                            elem_pos[i] -= elem_spd[i];
                            elem[i].style.left = elem_pos[i] + 'px';
                            if(elem_not_passed[i] && elem_pos[i] <= check_len && moving < len){
                                moving++;
                                elem_not_passed[i] = false;
                            }
                        }
                    }
                }
            }
        </script>
    </head>

    <body onload="show_results()">

        <div class="login_section">
            <div id="header">
                <h1>Orbit</h1>
                <h3>The Dynamic Library</h3>
            </div>
            <div class="container" id="login_div">
                <form action="index.php" method="post">
                    <p>Username:</p>
                    <input id = "login_ele" type="text" name="username"><br>
                    <p>Password:</p>
                    <input id="login_ele" type="password" name="password"><br>
                    <br>
                    <input type="submit" value="login" id="login_ele">
                </form>
                <?php if(!empty($_POST)){
                            echo 'No such email and password combination found';
                        }   ?>
                <br>
                <a href="create_new.php">New User</a>
            </div>
        </div>
        <div id="result_field"></div>
    </body>
</html>
