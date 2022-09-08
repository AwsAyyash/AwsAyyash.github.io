<?php

//session_save_path('../lab12/uploads');
//ini_set('session.save_path', '../lab12/uploads');

session_name('AwsSession');
session_start();


require_once("config.php");
?>
<html lang="en">

<head>

    <!-- Latest compiled and minified Bootstrap Core CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Exercise 13-1 | Using Cookies</title>
</head>

<body>

    <nav>
        <ul>
            <li>
                <p><a href="./index.html">Lab16 home page</a></p>
            </li>

        </ul>
    </nav>
    <header>
        <a href="./logout-session.php">
            <h1 style="text-align: right;">logout-session</h1>
        </a>
    </header>


    <?php

    function getLoginForm()
    {
        return "
<form action='" . $_SERVER['PHP_SELF'] . "' method='post' role='form'>
<div class ='form-group'>
  <label for='username'>Username</label>
  <input type='text' name='username' class='form-control'/>
</div>
<div class ='form-group'>
  <label for='pword'>Password</label>
  <input type='password' name='pword' class='form-control'/>
</div>
<input type='submit' value='Login' class='form-control' />

</form>";
    }
    // new function_#2
    function validLogin()
    {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        //very simple (and insecure) check of valuid credentials.
        $sql = "SELECT * FROM Credentials WHERE username=:user and
    Password=:pass";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':user', $_POST['username']);
        $statement->bindValue(':pass', $_POST['pword']);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $pdo = null;
            return true;
        }
        $pdo = null;
        return false;
    }
    ?>
    <div class="container theme-showcase" role="main">
        <div class="jumbotron">
            <h1>
                <?php
                //$loggedIn = false;
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (validLogin()) {
                        //echo "Welcome " . $_POST['username'];

                        //  $expiryTime = time() + 60 * 60 * 24;
                        // setcookie("username", $_POST['username'], $expiryTime);


                        $_SESSION['usernamee'] = $_POST['username'];
                        //header("Refresh:0");

                        // $loggedIn = true;
                    } else {
                        echo "login unsuccessful";
                    }
                } else {
                    echo "No Post detected";
                }

                if (isset($_SESSION['usernamee'])) {
                    echo "<br>Welcome " . $_SESSION['usernamee'];
                }

                ?>

            </h1>
        </div>
        <?php
        if (!isset($_SESSION['usernamee'])) {
            echo getLoginForm();
        } else {
            echo "This is some content";
        }
        ?>
    </div>
</body>

</html>