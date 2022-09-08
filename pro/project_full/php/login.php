<?php


session_name('AwsSession');

session_start();

//session_save_path('../images');
//ini_set('session.save_path', '/tmp');

require_once("config.php");
$user_type = "";


function getLoginForm()
{
    return "
    <h3 class='warning'>Red Input=required</h3>
    <div class='login'>
    
        <form action='" . $_SERVER['PHP_SELF'] . "' method='post' class='login_form'>
        

        <fieldset>
        <legend>Login</legend>
        
        <div class ='form_line'>
            <label for='user_name'>User Name:</label>
            <input type='text' name='user_name' placeholder='Enter your user name' required/>
        </div>

        

        <div class ='form_line'>
        <label for='password'>Password:</label>
        <input type='password' name='password' placeholder='Enter your password' required/>
        </div>
       
        <div class ='form_line'>
            <input type='submit' />
        </div>
        
        
        </fieldset>
        </form>
    </div>";
}
// new function_#2
function validLogin()
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
                <a href="../index_proj.html"><img src="../images/logo.jpg" alt="logo" title="company logo" /></a>
                <a href="../index_proj.html" class="inside_main_nav" id="company_name">Birzeit flat rental agency</a>
            </div>
            <ul class="col">
                <li>
                    <a href="../index_proj.html">Home Page</a>
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


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (validLogin()) {



                $_SESSION['user_name'] = $_POST['user_name'];

                $_SESSION['password'] = $_POST['password'];

                $_SESSION['logged_in'] = "true";




                if (empty($_SESSION['redirect_url']))
                    $_SESSION['redirect_url'] = "../index_proj.html";


                header("Location: ./" . $_SESSION['redirect_url']);
                exit;
            } else {
                echo  "<h2 class='warning'>* Password or user_name is not correct </h2>";
                echo getLoginForm();
            }
        } else {
            if (isset($_SESSION)  && isset($_SESSION['logged_in'])   && $_SESSION['logged_in'] == "true") {

                require_once('welcome.php');
                echo getWelcomeNote();
                //
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