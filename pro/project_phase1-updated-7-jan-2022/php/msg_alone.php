<?php

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
        $sql_msg = "SELECT * FROM `messages` WHERE msg_id =" . $msg_id . " AND reciver_user_id = " . $_SESSION['user_id'] . " ;";
        $result_flat = $pdo->query($sql_msg);
        $row_msg = $result_flat->fetch();
        //$_SESSION['preview_id'] = $row_msg['preview_id'];
        if (empty($row_msg) || $row_msg == null) {
            return "Permission denied!";
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
    global $indecator;
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
                $msg .= "<a href='./msg_alone.php?msg_id=" . $msg_id . "&accept_preview_id=" . $row_msg['preview_id'] . "'>Accept</a></p> </div>";
            else {
                $msg .= "<h3 class='warning'>Accepted</h3>";
                $is_accepted = true;
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


function notify_msgs($sender_id, $reciver_id, $title, $flat_id, $type)
{
    $body_msg = "A customer with id= " . $sender_id . ", and his/her mobile=" . $_SESSION['mobile'] .
        " wants to preview your flat with id="  . $flat_id . ".<br/>At ";

    $body_msg = "Dear customer your request to " . $type . " for the flat id#= " . $flat_id . "," .
        " has been approved by the owner-id=" . $sender_id . " and his/her name=" . $_SESSION['name'] . ", and his mobile" . $_SESSION['mobile'] . ". ";

    $sql_notify_msg = "INSERT INTO `messages` (`sender_user_id`, `reciver_user_id`, `title`, `body_of_meassage`, `flat_id`,`preview_id`) 
    VALUES 
    ('" . $sender_id . "', '" . $reciver_id . "', '" . $title . "', '" . $body_msg . "', '" . $flat_id . "', '" . $_SESSION['day_of_preview'] . "')"; // this is wrong change it by tomorow : 7-jan-friday

    global $pdo;
    $pdo->query($sql_notify_msg);
}

function update_preview_accept($preview_id)
{
    global $pdo;
    global $is_accepted;
    if (!$is_accepted) {
        $sql = "UPDATE `customer_request_preview` SET `owner_accept`='1' WHERE preview_id = " . $preview_id . ";";
        $res_update =  $pdo->query($sql);
        $rows_affected = $res_update->rowCount();
        if ($rows_affected > 0) {

            echo "<h3 class='warning'>Accepted succsesfuly!</h3>";
            // notify the customer of acceptance
            // notify_msgs($sender_id, $reciver_id, $title, $flat_id)
        }
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

        <?php

        // echo $form;
        //echo  $table_search_result;



        if (isset($_SESSION)  && isset($_SESSION['logged_in'])   && $_SESSION['logged_in'] == "true") {
            if ($_SESSION['user_type'] == "customer" || $_SESSION['user_type'] == "owner") {


                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['accept_preview_id']) && !empty($_GET['accept_preview_id'])) {
                    update_preview_accept($_GET['accept_preview_id']);
                }

                echo display_msg();
            } else {

                echo "<div><h2 class='welcome'>Welcome '" . $_SESSION['user_type']  . "', " . $_SESSION['user_name'] . ", Your ID= " . $_SESSION['user_id'] . "</h2>";

                echo "<h3>" . $_SESSION['user_type'] . " can not request preview, click <a href = '../index.html'>here</a> to go to main page</h3>";
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