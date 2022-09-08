<?php

session_name('AwsSession');

session_start();
require_once('config.php');



try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($e->getMessage());
}









function getTimeTableOfFlat()
{

    $arr = array("sunday" => 0, "monday" => 1, "tuseday" => 2, "wednesday" => 3, "thursday" => 4, "friday" => 5, "saturday" => 6);


    global $pdo;

    $id = PHP_INT_MAX;
    if (isset($_GET['id']) && !empty($_GET['id']) &&  is_numeric($_GET['id']))
        $id = $_GET['id'];



    $result = null;
    if ($id == PHP_INT_MAX) { // this to handle the invalid url changing

        $sql_flat = "SELECT * FROM `flat` WHERE  manager_status = 'approved' and id NOT IN ( SELECT flat_id FROM customer_rent_flat WHERE rent_end >= CURRENT_TIMESTAMP and owner_accept = 1)
        order by id ";
        $result_flat = $pdo->query($sql_flat);

        $row_flat = $result_flat->fetch();


        if (!empty($row_flat['id'])) {
            $id = $row_flat['id'];
            $_SESSION['flat_id'] = $row_flat['id'];
            $_SESSION['owner_id'] = $row_flat['user_id'];
            $sql = "SELECT * FROM `preview_time_table` WHERE flat_id = " . $id . "  ;";
            $result = $pdo->query($sql);
        }
    } else  if ($id != PHP_INT_MAX) {


        $sql_flat = "SELECT * FROM `flat` f WHERE  f.manager_status = 'approved' and f.id =" . $id  . " AND id NOT IN ( SELECT flat_id FROM customer_rent_flat WHERE rent_end >= CURRENT_TIMESTAMP and owner_accept = 1) ";
        $result_flat = $pdo->query($sql_flat);

        $row_flat = $result_flat->fetch();
        if (!empty($row_flat['id'])) {
            $_SESSION['owner_id'] = $row_flat['user_id'];


            $_SESSION['flat_id'] = $id;
            $sql = "SELECT * FROM `preview_time_table` WHERE flat_id = " . $id . "  ;";
            $result = $pdo->query($sql);
        }
    }



    $timeTablePreview_func = "
    <form method='post' action='request_preview.php'>";

    $timeTablePreview_func .= "   <table> <caption>Available Time slots for flat#" . $id . "</caption>

            <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time start</th>
                        <th>Time end</th>
                        <th>Telephone</th>
                        <th></th>
                    </tr>
            </thead>

            <tbody> ";


    if ($result != null) {
        while ($row = $result->fetch()) {


            $timeTablePreview_func .= "<tr "; // date("H:i:s")

            if ((date('w') == $arr["" . $row["day"] . ""] &&   strtotime(date("H:i:s")) > strtotime($row["time_end"]))) {
                $timeTablePreview_func .= " class='taken_slot'>" .
                    "<td>" . $row["day"] . "</td>" .
                    "<td>" . $row["time_start"] . "</td>" .
                    "<td>" . $row["time_end"] . "</td>" .
                    "<td>" . $row["telephone"] .  "</td>";
            } else {
                $timeTablePreview_func .=   "><td>" . $row["day"] . "</td>" .
                    "<td>" . $row["time_start"] . "</td>" .
                    "<td>" . $row["time_end"] . "</td>" .
                    "<td>" . $row["telephone"] .  "</td>" .

                    "<td>Choose  <input type='radio' name='day_of_preview' value='" . $row["id"] . "'>  </td>";
            }



            $timeTablePreview_func .=    " </tr>";
        }
    } else {
        return null;
    }
    return $timeTablePreview_func;
}

function getTimeTablePreview()
{








    $timeTablePreview =  getTimeTableOfFlat();
    if ($timeTablePreview != null) {

        $timeTablePreview .=    "          
    
        </tbody> 
            </table>
            <br><button type='submit' id='preview_button'>Request</button><br>
            
            </form>";

        return $timeTablePreview;
    }
    return null;
}

function  insert_preview_database($preview_id)
{
    $sql_insert = "INSERT INTO `customer_request_preview`(`user_id`, `preview_id`) 
    VALUES ('" . $_SESSION['user_id'] . "','" . $preview_id . "')";

    global $pdo;
    $pdo->query($sql_insert);


    // $row = $result->fetch()


}


function notify_msgs($sender_id, $reciver_id, $title, $flat_id)
{
    $body_msg = "A customer with id= " . $sender_id . ", and his/her mobile=" . $_SESSION['mobile'] .
        " wants to preview your flat with id="  . $flat_id . ".<br/>At ";

    $sql_notify_msg = "INSERT INTO `messages` (`sender_user_id`, `reciver_user_id`, `title`, `body_of_meassage`, `flat_id`,`preview_id`) 
    VALUES 
    ('" . $sender_id . "', '" . $reciver_id . "', '" . "Preview" . "', '" . $body_msg . "', '" . $flat_id . "', '" . $_SESSION['day_of_preview'] . "')";

    global $pdo;
    $pdo->query($sql_notify_msg);
}


function get_the_owner_info($owner_id)
{

    global $pdo;
    $sql_owner = "SELECT * FROM `users` WHERE user_id=" . $owner_id . ";";
    $resul = $pdo->query($sql_owner);
    $row_owner = $resul->fetch();
    $_SESSION['owner_mobile'] = $row_owner['mobile'];
    $_SESSION['owner_name'] = $row_owner['name'];
}

function remove_owner_session()
{
    unset($_SESSION['owner_mobile']);
    unset($_SESSION['owner_name']);
    unset($_SESSION['owner_id']);
    unset($_SESSION['flat_id']);
}



?>




<html lang="en">

<head>
    <title>Request Preview flat</title>
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
        <h1>Request Preview Flat</h1>

        <?php

        if (isset($_SESSION)  && isset($_SESSION['logged_in'])   && $_SESSION['logged_in'] == "true") {

            if ($_SESSION['user_type'] == "customer" || $_SESSION['user_type'] == "manager") {

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['day_of_preview']) && !empty($_POST['day_of_preview'])) {

                    $_SESSION['day_of_preview'] = $_POST['day_of_preview'];
                    insert_preview_database($_POST['day_of_preview']);


                    get_the_owner_info($_SESSION['owner_id']);
                    notify_msgs($_SESSION['user_id'], $_SESSION['owner_id'], "", $_SESSION['flat_id']);

                    echo  "<div><h2 class='warning'>Dear customer " . $_SESSION['name'] . ", your id='" . $_SESSION['user_id'] . "'</h2>" .
                        "<h3>You have requested to preview flat with id='" . $_SESSION['flat_id'] . "', Which is owned by '" . $_SESSION["owner_name"] .
                        "' and his id='" . $_SESSION["owner_id"] . "' and his mobile#='"  . $_SESSION['owner_mobile'] . "'<br>
                        Your request is succesfully done and has been sent to the owner.</h3><h3 class='warning'><em>Please
                        check your messages, and wait for the owner response!</em></h3></div>";

                    remove_owner_session();
                } else {

                    remove_owner_session();
                    $varr = getTimeTablePreview();
                    if ($varr != null) {
                        echo $varr;
                    } else {
                        header('Location: ./error.php?error=Permission denied!');
                    }
                }
            } else {

                require_once('welcome.php');
                echo noPermissionNote();
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