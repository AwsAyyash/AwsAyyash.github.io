<?php
session_name('AwsSession');

session_start();
require_once("config.php");

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($e->getMessage());
}

$is_accepted = false;










function display_msg()
{
    $msg_id = PHP_INT_MAX;
    global $pdo;
    if (isset($_GET['msg_id']) && !empty($_GET['msg_id']) &&  is_numeric($_GET['msg_id'])) {

        $msg_id = $_GET['msg_id'];
        $_SESSION['msg_id'] = $msg_id;
        $sql_msg = "SELECT * FROM `messages` WHERE msg_id =" . $msg_id . " AND reciver_user_id = " . $_SESSION['user_id'] . " ;";
        $result_flat = $pdo->query($sql_msg);
        $row_msg = $result_flat->fetch();
        //$_SESSION['preview_id'] = $row_msg['preview_id'];
        if (empty($row_msg) || $row_msg == null) {
            //return "Permission denied!";
            header('Location: ./error.php?error=Permission denied!');
        } else {
            // work is here!
            fill_database_viewed($msg_id); // set as viewd to be changed in css
            //return "the return form the function...."; // 
            return generate_msg($row_msg, $msg_id);
        }
    } else {
        //return "Set a value for msg id";
        header('Location: ./view_msg.php');
    }
}

function generate_msg($row_msg, $msg_id)
{



    $extra = "";
    global $pdo;
    if ($_SESSION['user_type'] == 'owner') {
        if ($row_msg['title'] == 'preview') {
            $sql_pre = "SELECT * FROM `preview_time_table` WHERE id= " . $row_msg['preview_id'] . ";";
            $res = $pdo->query($sql_pre);
            $row = $res->fetch();
            $extra .= "Day='" . $row['day'] . "', Start time=" . $row['time_start'] . ", End time=" . $row['time_end'];
            // echo "i got inside---";
        }
    }


    $msg = "";
    $msg .= "

    


                 
            <h3 >
                Sender:
                " . $row_msg['sender_user_id'] . "
            </h3>
           
            <h3 >
                <th>Reciver</th>
                <td>" . $row_msg['reciver_user_id'] . "</td>
             </h3>
             
             <h3 >
             Date:
                " . $row_msg['date_issued'] . "
             </h3>

       
        <div >
       
        <h2 id='msg_title'>" . $row_msg['title'] . "</h2>
            <p>" . $row_msg['body_of_meassage'] . "<br>" .  $extra
        .
        "<br>
        ";
    if ($_SESSION['user_type'] == 'owner') {
        if ($row_msg['title'] == 'preview') {
            $sql_check_if_accepted = "SELECT * FROM `customer_request_preview` WHERE preview_id =" . $row_msg['preview_id'] . " AND owner_accept=0;";
            $res_check =  $pdo->query($sql_check_if_accepted);
            $row_check =  $res_check->fetch();

            if (!empty($row_check) && $row_check != null)
                $msg .= "<a href='./msg_alone.php?msg_id=" . $msg_id . "&accept_id=" . $row_msg['preview_id'] . "'>Accept</a></p> </div>";
            else {
                $msg .= "<h3 class='warning'>Accepted</h3>";
            }
        } else {
            $sql_not_accepted = "SELECT * FROM `customer_rent_flat` WHERE flat_id =" . $row_msg['flat_id'] . " AND
            user_id =" . $row_msg['sender_user_id'] . " AND owner_accept = 0 ORDER BY rent_start DESC;";
            $res =  $pdo->query($sql_not_accepted);
            $row =  $res->fetch();

            if (!empty($row) && $row != null)
                $msg .= "<a href='./msg_alone.php?msg_id=" . $msg_id . "&user_id=" . $row['user_id'] . "&flat_id=" . $row['flat_id'] . "&rent_start=" . $row['rent_start'] . "'>Accept</a></p> </div>";
            else {
                $msg .= "<h3 class='warning'>Accepted</h3>";
            }
        }
    }







    return $msg;
}
function fill_database_viewed($msg_id)
{


    global $pdo;

    $sql = "UPDATE `messages` SET `is_viewed`='1' WHERE msg_id =" . $msg_id . ";";
    $pdo->query($sql);
}


function notify_msgs($sender_id, $reciver_id, $title, $flat_id, $type, $preview_id) // type: is : preview , rent, // title: preview response ,...
{



    $body_msg = "Dear customer your request to " . $type . " for the flat id#= " . $flat_id . "," .
        " has been approved by the owner-id=-" . $sender_id . "- and his/her name=-" . $_SESSION['name'] . "-, and his mobile=-" . $_SESSION['mobile'] . "-. ";

    if ($type == "preview") {
        $sql_notify_msg = "INSERT INTO `messages` (`sender_user_id`, `reciver_user_id`, `title`, `body_of_meassage`, `flat_id`,`preview_id`) 
            VALUES 
            ('" . $sender_id . "', '" . $reciver_id . "', '" . $title . "', '" . $body_msg . "', '" . $flat_id . "', '" . $preview_id . "')"; // this is wrong change it by tomorow : 7-jan-friday

    } else {
        $sql_notify_msg = "INSERT INTO `messages` (`sender_user_id`, `reciver_user_id`, `title`, `body_of_meassage`, `flat_id`) 
            VALUES 
            ('" . $sender_id . "', '" . $reciver_id . "', '" . $title . "', '" . $body_msg . "', '" . $flat_id  . "')"; // this is wrong change it by tomorow : 7-jan-friday

    }



    global $pdo;
    $pdo->query($sql_notify_msg);
}

function prepare_data_for_msg_respone()
{

    $sql_msg_get = "SELECT * FROM `messages` WHERE msg_id =" . $_SESSION['msg_id'] . ";";
    global $pdo;
    $res = $pdo->query($sql_msg_get);
    $row_msg = $res->fetch();
    if ($row_msg != null &&  !empty($row_msg)) {
        if ($row_msg['title'] == "preview")
            notify_msgs($row_msg['reciver_user_id'], $row_msg['sender_user_id'], "preview_reply", $row_msg['flat_id'], "preview", $row_msg['preview_id']);
        else
            notify_msgs($row_msg['reciver_user_id'], $row_msg['sender_user_id'], "rent_reply", $row_msg['flat_id'], "rent", "NULL");
    }
    unset($_SESSION['msg_id']);
}

function update_preview_accpet($preview_id)
{
    global $pdo;
    $sql = "UPDATE `customer_request_preview` SET `owner_accept`='1' WHERE preview_id = '" . $preview_id . "';";
    $res_update =  $pdo->query($sql);
    $rows_affected = $res_update->rowCount();
    if ($rows_affected > 0) {

        echo "<h3 class='warning'>Accepted succsesfuly!</h3>";
        // notify the customer of acceptance
        prepare_data_for_msg_respone(); // this will cal the notify function
    }
}

function update_rent_accpet($user_id, $flat_id, $rent_start)
{
    // $_GET['user_id'], $_GET['flat_id'], $_GET['rent_start'];
    global $pdo;

    $sql = "UPDATE `customer_rent_flat` SET `owner_accept`='1' WHERE user_id = '" . $user_id . "' AND flat_id='" . $flat_id . "' AND rent_start='" . $rent_start . "'  ;";
    $res_update =  $pdo->query($sql);
    $rows_affected = $res_update->rowCount();
    if ($rows_affected > 0) {

        echo "<h3 class='warning'>Accepted succsesfuly!</h3>";
        // notify the customer of acceptance

        prepare_data_for_msg_respone(); // this will cal the notify function
    }
}

?>


<html lang="en">

<head>
    <title>Message</title>
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

        <?php





        if (isset($_SESSION)  && isset($_SESSION['logged_in'])   && $_SESSION['logged_in'] == "true") {
            if ($_SESSION['user_type'] == "customer" || $_SESSION['user_type'] == "owner") {


                $var = display_msg();
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['accept_id']) && !empty($_GET['accept_id'])) {
                    update_preview_accpet($_GET['accept_id']);
                    $var = display_msg();
                } else if (
                    $_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['user_id']) && !empty($_GET['user_id'])
                    && isset($_GET['flat_id']) && !empty($_GET['flat_id']) && isset($_GET['rent_start']) && !empty($_GET['rent_start'])
                ) {
                    update_rent_accpet($_GET['user_id'], $_GET['flat_id'], $_GET['rent_start']);
                    $var = display_msg();
                }
                echo $var;
            } else {

                echo "<div><h2 class='welcome'>Welcome '" . $_SESSION['user_type']  . "', " . $_SESSION['user_name'] . ", Your ID= " . $_SESSION['user_id'] . "</h2>";

                echo "<h3>" . $_SESSION['user_type'] . " can not request preview, click <a href = '../index_proj.html'>here</a> to go to main page</h3>";
                echo "<h3>To logout click <a href = './logout.php'>here</a>!</h3></div>";
            }
        } else {
            $arr = explode("/", $_SERVER['REQUEST_URI']);

            $_SESSION['redirect_url'] = end($arr);

            header('Location: ./login.php');
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