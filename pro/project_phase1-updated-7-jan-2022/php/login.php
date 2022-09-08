<?php

session_start();

require_once("config.php");
$user_type = "";


function getLoginForm()
{
    return "
  
    <div class='login'>
        <form action='" . $_SERVER['PHP_SELF'] . "' method='post' class='login_form'>
        

        <fieldset>
        <legend>Login</legend>

        <div class ='form_line'>
            <label for='user_name'>User Name:</label>
            <input type='text' name='user_name' placeholder='Enter your user name'/>
        </div>

        

        <div class ='form_line'>
        <label for='password'>Password:</label>
        <input type='password' name='password' placeholder='Enter your password' />
        </div>
       
        <div class ='form_line'>
            <input type='submit' />
        </div>
        
        
        </fieldset>
        </form>
    </div>";
}
// new function_#2
function validLogin(array $row)
{
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM `users` WHERE user_name='" . $_POST['user_name'] .
        "' and password= '" . $_POST['password'] . "'";

    $result = $pdo->query($sql);
    $row = $result->fetch();
    $pdo = null;
    if ($result->rowCount() > 0) {

        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['id_card'] = $row['id_card'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['mobile'] = $row['mobile'];
        $_SESSION['user_type'] = $row['user_type'];

        return true;
    }
    return false;
}

?>

<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <header>
        <nav class="multi_column">
            <div class="col">
                <a href="../index.html"><img src="../images/logo.jpg" alt="logo" title="company logo" /></a>
                <a href="../index.html" class="inside_main_nav" id="company_name">Birzeit flat rental agency</a>
            </div>
            <ul class="col">
                <li>
                    <a href="../index.html">Home Page</a>
                </li>
                <li>
                    <a href="./flats.php">Flats</a>
                </li>
                <li>
                    <a href="./search_flats.php">Search Flats</a>
                </li>

                <li>
                    <a href="./login.php">Login</a>
                </li>
                <li>
                    <a href="./register.php">Register</a>
                </li>
                <li>
                    <a href="../aboutus.html">About Us</a>
                </li>
                <li>
                    <a href="./logout.php">logout</a>
                </li>
                <li>
                    <a href="./view_msg.php">Messages</a>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Login page</h1>


        <?php
        //$loggedIn = false;
        $row = array();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (validLogin($row)) {



                $_SESSION['user_name'] = $_POST['user_name'];

                $_SESSION['password'] = $_POST['password'];

                $_SESSION['logged_in'] = "true";




                if (empty($_SESSION['redirect_url']))
                    $_SESSION['redirect_url'] = "../index.html";


                header("Location: ./" . $_SESSION['redirect_url']);
            } else {
                echo  "<h2 class='warning'>* Password or user_name is not correct </h2>";
                echo getLoginForm();
            }
        } else {
            if (isset($_SESSION)  && isset($_SESSION['logged_in'])   && $_SESSION['logged_in'] == "true") {
                echo "<div><h2 class='welcome'>Welcome '" . $_SESSION['user_type']  . "', " . $_SESSION['user_name'] . ", Your ID= " . $_SESSION['user_id'] . "</h2>";
                echo "<h3>You are already logged in, click <a href = '../index.html'>here</a> to go to main page</h3>";
                echo "<h3>You are already logged in, to logout click <a href = './logout.php'>here</a>!</h3></div>";
            } else
                echo getLoginForm();
        }








        ?>
    </main>
    <footer>
        <em>
            Birzeit, Palestine
            <a href="mailto:aws.ayyash.1@gmail.com"> E-mail</a> &copy; 2021.</em>
    </footer>
</body>

</html>