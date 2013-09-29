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
                <div id="first_time">
                    First time here? <a id="reg" href='#'>Register?</a><br />
                    Or <a id="auth" href='#'>sign in?</a>
                </div>
                <div id="registration" style="display: none;">
                    <form method="POST" action="">
                        Login:<br /><input type="text" name="login" id="login" /><br />
                        Password:<br /><input type="password" name="password" id="password" /><br />
                        <a id="lets_reg" href="#">Go!</a> 
                    </form>
                </div>
                <div id="authorization" style="display: none;">
                    <form method="POST" action="">
                        Login:<br /><input type="text" name="name" /><br />
                        Password:<br /><input type="password" name="pass" /><br />
                        <a id="sign_in" href="#">Sign in!</a> 
                    </form>
                </div>
                <div id="errors">
                </div>
                <div id="profile">
                </div>
            <?php } else { ?>
                <div>
                    <?php echo "Hello, " . $_COOKIE['username']; ?>
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

        $('#lets_reg').click(function(e) {
            e.preventDefault();

            var url = 'validate.php';
            var data = $('#registration form').serialize();

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

        });

        $('#sign_in').click(function(e) {
            e.preventDefault();

            var url = 'validate.php';
            var data = $('#authorization form').serialize();

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
                        $('#authorization').fadeOut(100);
                        $('#profile').html('Hello, ' + response.username);
                    }
                }
            });

        });
    });
</script>