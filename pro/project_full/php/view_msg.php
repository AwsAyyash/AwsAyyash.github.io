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







function get_msgs_owner_customer()
{
    $sql = "SELECT * FROM `messages` WHERE
      reciver_user_id = " . $_SESSION['user_id'] . "
     ORDER BY msg_id DESC;";

    global $pdo;


    $result_msgs =  $pdo->query($sql);
    $returned_msgs = "
    <table> 

                <thead>
                        <tr>
                            <th>Date</th>
                            <th>Sender</th>
                            <th>Reciver</th>
                            <th>Title</th>
                            <th></th>
                        </tr>
                </thead>
                
                <tbody>";




    while ($row_msg = $result_msgs->fetch()) {
        $returned_msgs .= "<tr ";
        if ($row_msg['is_viewed'] == "0") {
            $returned_msgs .= " class='not_viewd_msg'>" .
                "<td>" . $row_msg["date_issued"] . "</td>" .
                "<td>" . $row_msg["sender_user_id"] . "</td>" .
                "<td>" . $row_msg["reciver_user_id"] . "</td>" .
                "<td>" . $row_msg["title"] .  "</td>";
        } else {
            $returned_msgs .=   "><td>" . $row_msg["date_issued"] . "</td>" .
                "<td>" . $row_msg["sender_user_id"] . "</td>" .
                "<td>" . $row_msg["reciver_user_id"] . "</td>" .
                "<td>" . $row_msg["title"] .  "</td>";
        }

        $returned_msgs .=   " <td><a href='./msg_alone.php?msg_id=" . $row_msg["msg_id"] .  "'>View Message</a></td>" .
            "</tr>";
    }


    $returned_msgs .= "</tbody></table>";
    return $returned_msgs;
}

function get_msgs_manager()
{


    $sql_to_approve = "SELECT * FROM users, flat WHERE users.user_id = flat.user_id and flat.manager_status ='pending';
    ORDER BY flat.id;";


    global $pdo;


    $result_flat =  $pdo->query($sql_to_approve);
    //$row_flat_to_approve = $result_flat->fetch();

    $res_data = "      
        <form method='post' action='view_msg.php'> 
        <table> 
        <caption>Pending Flats</caption>


                 <thead>
                       <tr>
                            <th></th>
                            <th>Owner id</th>
                            <th>Owner Name</th>
                            <th>Flat id</th>
                            </tr>
                    </thead>
                    
                    <tbody>";
    while ($row_flat_to_approve = $result_flat->fetch()) {

        $res_data .= "<tr>" .
            "<td>Approve <input type='checkbox' name='to_be_approved[]' value='" . $row_flat_to_approve["id"] . "'> </td>" .
            "<td>" . $row_flat_to_approve['user_id'] . "</td>" .
            "<td>" . $row_flat_to_approve['name'] . "</td>" .
            "<td>" . $row_flat_to_approve['id'] . "</td>" .
            " </tr>";
    }
    $res_data .= "
     
            </tbody>
        </table>

        <input type='submit' id='preview_button'>
    </form>";

    return $res_data;
}

function rented_flat_to_manager() // only show rented and approved : just to see, no any response form the manager
{
    $sql_with_customer = "SELECT crf.user_id AS customer_id, crf.flat_id AS flat_id, 
            crf.rent_start,crf.rent_end, crf.total_amount, f.user_id AS owner_id, u.name as customer_name
            FROM customer_rent_flat crf, flat f, users u 
            WHERE
            u.user_id = crf.user_id AND f.id = crf.flat_id AND  crf.owner_accept = 1 AND f.manager_status ='approved'
            ORDER BY rent_start DESC;";

    $sql_with_owner = "SELECT crf.user_id AS customer_id, crf.flat_id AS flat_id, 
        crf.rent_start,crf.rent_end,crf.total_amount,f.user_id AS owner_id, u.name as owner_name
        FROM customer_rent_flat crf, flat f, users u 
        WHERE
        u.user_id = f.user_id AND f.id = crf.flat_id AND  crf.owner_accept = 1 AND f.manager_status ='approved'
        ORDER BY rent_start DESC;";

    global $pdo;
    $res_cus = $pdo->query($sql_with_customer);
    $res_owner = $pdo->query($sql_with_owner);

    $res_data = "      
            <table> 
            <caption>Rented Flats</caption>
                 <thead>
                       <tr>
                            <th>Customer id</th>
                            <th>Customer Name</th>

                            <th>Owner id</th>
                            <th>Owner Name</th>

                            <th>Flat id</th>
                            <th>Rent Start</th>
                            <th>Rent End</th>
                            <th>Total amout of rent</th>
                            </tr>
                    </thead>
                    
                    <tbody>";
    while ($row_cus = $res_cus->fetch()) {
        $row_owner = $res_owner->fetch();
        $res_data .= "<tr>" .
            "<td>" . $row_cus['customer_id'] . "</td>" .
            "<td>" . $row_cus['customer_name'] . "</td>" .

            "<td>" . $row_cus['owner_id'] . "</td>" .
            "<td>" . $row_owner['owner_name'] . "</td>" .

            "<td>" . $row_cus['flat_id'] . "</td>" .
            "<td>" . $row_cus['rent_start'] . "</td>" .
            "<td>" . $row_cus['rent_end'] . "</td>" .
            "<td>" . $row_cus['total_amount'] . "$</td>" .

            " </tr>";
    }
    $res_data .= "
     
            </tbody>
        </table>

    ";
    return $res_data;
}


function fill_database_approved_flat()
{


    global $pdo;
    foreach ($_POST['to_be_approved']  as $key => $value) {
        $sql = "UPDATE `flat` SET `manager_status`='approved' WHERE id =" . $value . ";";
        $pdo->query($sql);
    }
}
?>


<html lang="en">

<head>
    <title>Messages</title>
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
        <h1>Messages:</h1>
        <?php





        if (isset($_SESSION)  && isset($_SESSION['logged_in'])   && $_SESSION['logged_in'] == "true") {
            if ($_SESSION['user_type'] == "customer" || $_SESSION['user_type'] == "owner") {


                echo get_msgs_owner_customer();
            } else {
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['to_be_approved']) && !empty($_POST['to_be_approved'])) {
                    fill_database_approved_flat();
                }
                echo get_msgs_manager();


                echo rented_flat_to_manager();
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