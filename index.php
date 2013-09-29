<?php
//con db
include "db/config.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>RegAuth</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    </head>
    <body>
        <div id="wrapper">
            <?php
            if (empty($_COOKIE['id'])) {
                ?>
                <a href="#" id="home">Home</a>
                <div id="first_time">
                    First time here? <a id="reg" href='#'>Register?</a><br />
                    Or <a id="auth" href='#'>sign in?</a>
                </div>
                <div id="registration" style="display: none;">
                    <h1>Registration:</h1>
                    <form method="POST" action="">
                        Login:<br /><input type="text" name="login" id="login" /><br />
                        Password:<br /><input type="password" name="password" id="password" /><br />
                        <a id="lets_reg" href="#">Go!</a> 
                    </form>
                </div>
                <div id="authorization" style="display: none;">
                    <h1>Authorization:</h1>
                    <form method="POST" action="">
                        Login:<br /><input type="text" name="name" id="name" /><br />
                        Password:<br /><input type="password" name="pass" id="pass" /><br />
                        <a id="sign_in" href="#">Sign in!</a> 
                    </form>
                </div>
                <div id="errors">
                </div>

            <?php } else { ?>
                <div id="prof">
                    <?php echo "Hello, " . $_COOKIE['username']; ?>
                    <a class="exit" href="#">Logout</a><br />
                    <form enctype="multipart/form-data" method="post" action="index.php">
                        <label for="file">Upload avatar:</label>
                        <input type="file" name="avatar" id="avatar" /> 
                        <br />
                        <input type="submit" value="Upload!" id="upload"> 
                    </form>
                    <?php
                    $id = $_COOKIE['id'];
                    $avatar = $_FILES['avatar']['name'];

                    $uploaddir = 'uploads/';
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploaddir . $_FILES['avatar']['name'])) {
                        print "Done!<br />";
                        echo '<img style="width:100px;height:auto;" src="uploads/' . $avatar . '">';
                        mysql_query("UPDATE `users` SET `avatar`='$avatar' WHERE `id` = $id") or die("<br>ERROR: " . mysql_error());
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    </body>
</html>
<script>
    $(document).ready(function() {
        $('#reg').click(function(e) {
            e.preventDefault();

            $('#first_time').fadeOut(300);
            $('#registration').fadeIn(100);
        });

        $('#auth').click(function(e) {
            e.preventDefault();

            $('#first_time').fadeOut(300);
            $('#authorization').fadeIn(100);
        });

        $('#lets_reg').click(function(e) {
            e.preventDefault();

            var url = 'validate.php';
            var data = $('#registration form').serialize();
            var login = $('#login').val();
            var password = $('#password').val();

            if (login != '' && password != '') {
                $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: 'json',
                    data: data,
                    success: function(response) {
                        if (response.errors != '') {
                            $('#errors').html(response.errors);
                        } else {
                            $('#errors').hide();
                        }

                        if (response.success == 'ok') {
                            $('#registration').fadeOut(100);
                            alert('Thank you! You can sign in now!');
                            $('#authorization').fadeIn(100);
                        }
                    }
                });
            } else {
                $('#errors').html('Enter your login and password, please!');
            }

        });

        $('#sign_in').click(function(e) {
            e.preventDefault();

            var url = 'validate.php';
            var data = $('#authorization form').serialize();
            var login = $('#name').val();
            var password = $('#pass').val();

            if (login != '' && password != '') {
                $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: 'json',
                    data: data,
                    success: function(response) {
                        if (response.errors != '') {
                            $('#errors').html(response.errors);
                        } else {
                            $('#errors').hide();
                        }

                        if (response.success == 'ok') {
                            window.location.reload();
                        }
                    }
                });
            } else {
                $('#errors').html('Enter your login and password, please!');
            }

        });

        $('.exit').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'validate.php',
                dataType: 'json',
                data: 'exit=true',
                success: function(response) {
                    if (response.success == 'ok') {
                        window.location.reload();
                    }
                }
            });
        });

        $('#home').click(function(e) {
            e.preventDefault();

            window.location.reload();
        });
    });
</script>