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

$id = PHP_INT_MAX;
$owner_of_this_flat;

$row_flat;
$cost_month_of_the_flat;


function notify_msgs($sender_id, $reciver_id, $title, $flat_id)
{
    $body_msg = "A customer with id= " . $sender_id . ", and his/her mobile=" . $_SESSION['mobile'] . " wants to rent your flat with id="  . $flat_id;

    $sql_notify_msg = "INSERT INTO `messages` (`sender_user_id`, `reciver_user_id`, `title`, `body_of_meassage`, `flat_id`) 
    VALUES 
    ('" . $sender_id . "', '" . $reciver_id . "', '" . "Rent" . "', '" . $body_msg . "', '" . $flat_id . "')";

    global $pdo;
    $pdo->query($sql_notify_msg);
}
function display_flat($row)
{


    $_SESSION['cost_month'] = $row['cost_month'];
    $res_falt = "";



    $furnished = "No";
    if ($row["furnished"] == "1") {
        $furnished = "Yes";
    }

    $has_heating_system = "No";
    if ($row["has_heating_system"] == "1") {
        $has_heating_system = "Yes";
    }
    $has_air_condition = "No";
    if ($row["has_air_condition"] == "1") {
        $has_air_condition = "Yes";
    }

    $has_access_control = "No";
    if ($row["has_access_control"] == "1") {
        $has_heating_system = "Yes";
    }

    $has_car_parking = "No";
    if ($row["has_car_parking"] == "1") {
        $has_car_parking = "Yes";
    }

    $backyard = "No";
    if ($row["backyard"] != "none") {
        $backyard = $row["backyard"];
    }

    $has_playground = "No";
    if ($row["has_playground"] == "1") {
        $has_playground = "Yes";
    }

    $has_storage = "No";
    if ($row["has_storage"] == "1") {
        $has_storage = "Yes";
    }

    $res_falt .= "<ul class ='flat_det'>";
    $res_falt .=  "<li><em>Cost per month:</em> " . $row["cost_month"] . "$</li>" .

        "<li><em>Available from:</em> " . $row["date_available"] . "</li>" .
        "<li><em>location:</em> " . $row["location"] . "</li>" .
        "<li><em>Number of bedrooms:</em> " . $row["num_of_bedrooms"] . " rooms</li>" .
        "<li><em>Number of bathrooms:</em> " . $row["num_of_bathrooms"] . " rooms</li>" .
        "<li><em>Rent conditions:</em> " . $row["rent_condition"] . "</li>" .
        "<li><em>Size:</em> " . $row["size"] . " m<sup>2</sup></li>" .
        "<li><em>furnished:</em> " . $furnished . "</li>" .
        "<li><em>Has heating system:</em> " . $has_heating_system . "</li>" .
        "<li><em>Has air condition:</em> " . $has_air_condition . "</li>" .
        "<li><em>Has access control:</em> " . $has_access_control . "</li>" .
        "<li><em>About the location:</em> " . $row["about_location"] . "</li>" .


        "<li><em>Extra features:</em></li>"  .
        "<ul class='extra_features'>" .
        "<li><em>Has car parking:</em> " . $has_car_parking . "</li>" .
        "<li><em>backyard:</em> " . $backyard . "</li>" .
        "<li><em>Has playground:</em> " . $has_playground . "</li>" .
        "<li><em>Has storage:</em> " . $has_storage . "</li>" .
        "</ul>";

    $res_falt .= "</ul>";
    return $res_falt;
}

function get_form($pdo)
{

    global $id;

    if (isset($_GET['id']) && !empty($_GET['id']))
        $id = $_GET['id'];

    if ($id != PHP_INT_MAX) // this to handle the invalid url changing
        $sql = "SELECT * FROM `flat` WHERE id= " . $id . " AND manager_status = 'approved'  and id NOT IN ( SELECT flat_id FROM customer_rent_flat WHERE rent_end >= CURRENT_TIMESTAMP  AND owner_accept=1)";
    else {
        $sql = "SELECT * FROM `flat` WHERE  manager_status = 'approved' and id NOT IN ( SELECT flat_id FROM customer_rent_flat WHERE rent_end >= CURRENT_TIMESTAMP AND owner_accept=1)
         order by id ";
    }
    $result = $pdo->query($sql);
    global $row_flat;
    $row_flat = $result->fetch(); // this row has the flat we want to display

    if (empty($row_flat['id']) || $row_flat['manager_status'] != "approved") {


        $sql = "SELECT * FROM `flat` WHERE  manager_status = 'approved' and id NOT IN ( SELECT flat_id FROM customer_rent_flat WHERE rent_end >= CURRENT_TIMESTAMP AND owner_accept=1) order by id;";
        $result = $pdo->query($sql);

        $row_flat = $result->fetch(); // this row has the flat we want to display


    }
    $id  = $row_flat['id'];
    global $owner_of_this_flat;
    $owner_of_this_flat = $row_flat['user_id']; // the owner id.
    $_SESSION['cost_month'] = $row_flat['cost_month'];
    global $cost_month_of_the_flat;
    $_SESSION['flat_id'] = $id;
    $cost_month_of_the_flat = $row_flat['cost_month'];
    echo "<h2>You are renting the flat with referance number: " . $id . "</h2>";

    echo  display_flat($row_flat); // call the form to be displayed on the page as html 

    echo display_owner_details($owner_of_this_flat);
}

function get_owner_address($owner_id)
{

    global $pdo;

    $sql_address = "SELECT * FROM `address_users` WHERE user_id =" . $owner_id . ";";

    $result_address =  $pdo->query($sql_address);
    $row_address = $result_address->fetch();

    $res_address = "" .
        "<h3>Owner Address</h3>" .
        "<li><em>House Number:</em> " . $row_address["house_number"] . "</li>" .
        "<li><em>Street Name:</em> " . $row_address["street_name"] . "</li>" .
        "<li><em>City :</em> " . $row_address["city"] . "</li>" .
        "<li><em>Postal code :</em> " . $row_address["postal_code"] . "</li>";
    return $res_address;
}
$row_owner;
function display_owner_details($owner_of_this_flat)
{

    global $pdo;

    $sql_get_owner = "SELECT * FROM `users` WHERE user_type = 'owner' AND user_id = " . $owner_of_this_flat . ";";

    $result_owner =  $pdo->query($sql_get_owner);
    $row_owner = $result_owner->fetch();

    $res_owner_det =  "<h2>Owner</h2>" .
        "<ul class ='flat_det col'>" .
        "<li><em>Name:</em> " . $row_owner["name"] . "</li>" .
        "<li><em>Id:</em> " . $row_owner["user_id"] . "</li>" .
        "<li><em>Mobile:</em> " . $row_owner["mobile"] . "</li>" .

        ("" . get_owner_address($row_owner["user_id"])) . "";


    $_SESSION['owner_id'] = $row_owner["user_id"];
    $_SESSION['owner_name'] = $row_owner["name"];
    $_SESSION['owner_mobile'] = $row_owner["mobile"];


    return $res_owner_det;
}




$form_rent_period = <<<EDO
<h3 class="warning">Red Input=required</h3>
            <form method="post" action="./rent.php">
               
            <div class = 'form_line'>
                    
            <label>Start time to rent:</label> <input type="date" name="rent_start" required> 
            <label>End time for the rent:</label> <input type="date" name="rent_end" required>
                    <button type ="submit">Next</button>

                </div>
            </form>
EDO;


$form3_confirm = <<<EDO
            <form method="post" action="./rent.php">
                <fieldset>
                    <legend>Rent this flat</legend>
                    <label class='warning'>Are you sure you want to rent this flat?</label>
                     <input type="checkbox" name="ok" value="1" required>
                    <button type ="submit">Confirm</button>

                </fieldset>
            </form>
EDO;

$form_bank_details_customer = <<<EDO
<h3 class="warning">Red Input=required</h3>
            <form method="post" action="./rent.php">
                <fieldset>
                    <legend>Provide your bank details:</legend>
                    
                   

                    <div class = "form_line">

                    <label for="credit_card_type">Credit card type:</label>

                    111_Visa <input type="radio"  name="credit_card_type" value="111" checked>
                    222_MasterCard <input type="radio"  name="credit_card_type" value="222">
                    333_American Express <input type="radio"  name="credit_card_type" value="333">



                    </div>

                    <div class = "form_line">
                    <label for="credit_card_number">Credit card number:</label>
               
                      <input type='number' min='100000' max='999999' step='000001' name='credit_card_number' placeholder='Credit card number'  required>
                    </div>
               
               
                    <div class = "form_line">
                    <label for="credit_card_expire">Date of credit card expire:</label>
               
                      <input type='date' name='credit_card_expire' placeholder='Date of credit card expire'  required>
                    </div>
               
                    <div class = "form_line">
                    <label for="credit_card_bank_name">Bank name:</label>
               
                      <input type='text' name='credit_card_bank_name' placeholder='Bank name of the Credit card'  required>
                    </div>

                    <div class = "form_line">
                    <button type ="submit">Confirm</button>

                    </div>

                </fieldset>
            </form>
EDO;





$total_cost_full_rent_time;
function calculate_total_cost_of_renting($id)
{
    global $pdo;




    $sql_date_differance = "SELECT DATEDIFF( '" . $_SESSION['rent_end'] . "', '" . $_SESSION['rent_start'] . "') AS DateDiff;";

    $result_differance = $pdo->query($sql_date_differance);

    $row = $result_differance->fetch();
    $diff_int = (int) $row['DateDiff'];


    //$_SESSION['cost_month'] = $row_flat['cost_month'];
    global  $total_cost_full_rent_time;
    $total_cost_full_rent_time = round((float)(($diff_int / 30.0) * ((float) $_SESSION['cost_month'])), 2);
    return $total_cost_full_rent_time;
}

function add_the_rent_to_database()
{

    global $pdo;
    global $id;

    $_SESSION['total_cost_full_rent_time']  =  calculate_total_cost_of_renting($id);

    $sql_insert_rent = "INSERT INTO `customer_rent_flat`
    (`user_id`, `flat_id`, `credit_card_type` ,`credit_card_number`, `credit_card_expire`,
     `credit_card_bank_name`, `rent_start`, `rent_end`, `total_amount`) 
    VALUES 
    ('" . $_SESSION['user_id'] . "','" . $_SESSION['flat_id'] . "','" . $_SESSION['credit_card_type'] . "','" . $_SESSION['credit_card_number'] . "','" . $_SESSION['credit_card_expire'] .
        "','" . $_SESSION['credit_card_bank_name'] . "','" . $_SESSION['rent_start'] . "','" . $_SESSION['rent_end'] . "','" . $_SESSION['total_cost_full_rent_time'] . "')
    ";


    $pdo->query($sql_insert_rent);

    $res_confirm_string = "<div><h2>Dear customer " . $_SESSION['name'] . ", your id='" . $_SESSION['user_id'] . "'</h2>" .
        "<h3>You have requested to rent flat with id='" . $_SESSION['flat_id'] . "', Which is owned by '" . $_SESSION["owner_name"] .
        "' and his id='" . $_SESSION["owner_id"] . "' and his mobile#='"  . $_SESSION['owner_mobile'] . "'<br>" .
        "From '" . $_SESSION['rent_start'] . "'" . ", To '" . $_SESSION['rent_end'] . "'</h3>" .
        "<h3>Total cost=" . $_SESSION['total_cost_full_rent_time'] . "\$</h3>" .
        "<h3 class='warning'>Once the owner replies, you will be notified!</h3></div>";


    return $res_confirm_string;
}

//echo $diff_int;




function un_set_session_current()
{

    unset($_SESSION['rent_end']);
    unset($_SESSION['rent_start']);
    unset($_SESSION['credit_card_type']);
    unset($_SESSION['credit_card_number']);
    unset($_SESSION['credit_card_expire']);
    unset($_SESSION['credit_card_bank_name']);
    unset($_SESSION['cost_month']);
    unset($_SESSION['flat_id']);
    unset($_SESSION['total_cost_full_rent_time']);
    unset($_SESSION['owner_id']);

    unset($_SESSION['owner_name']);
    unset($_SESSION['owner_mobile']);
}

?>

<html lang="en">

<head>
    <title>Rent flat</title>
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
        <h1>Rent flats</h1>

        <?php




        if (isset($_SESSION)  && isset($_SESSION['logged_in'])   && $_SESSION['logged_in'] == "true") {




            if ($_SESSION['user_type'] == "customer" || $_SESSION['user_type'] == "manager") {

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['credit_card_bank_name']) && !empty($_POST['credit_card_bank_name'])) {

                    $_SESSION['credit_card_type'] = $_POST['credit_card_type'];
                    $_SESSION['credit_card_number'] = $_POST['credit_card_number'];
                    $_SESSION['credit_card_expire'] = $_POST['credit_card_expire'];
                    $_SESSION['credit_card_bank_name'] = $_POST['credit_card_bank_name'];

                    echo  add_the_rent_to_database();
                    // print the confirmation to the customer + the owner (notify)
                    notify_msgs($_SESSION['user_id'], $_SESSION['owner_id'], "Rent", $_SESSION['flat_id']);
                    // after notifiing the owner => remove the $_session data

                    un_set_session_current();
                    // print_r($_SESSION);
                } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ok']) && !empty($_POST['ok'])) {


                    if ($_POST['ok'] == "1") {
                        echo  $form_bank_details_customer;
                        //print_r($_SESSION);
                    } else {
                        get_form($pdo);

                        // echo the form of period fo rent
                        echo $form_rent_period;
                        // print_r($_SESSION);
                    }
                } else if (
                    $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rent_end']) && !empty($_POST['rent_end'])
                    && isset($_POST['rent_start']) && !empty($_POST['rent_start'])
                ) {
                    $_SESSION['rent_end'] = $_POST['rent_end'];
                    $_SESSION['rent_start'] = $_POST['rent_start'];

                    echo $form3_confirm;
                } else {
                    get_form($pdo);


                    // echo the form of period fo rent
                    echo $form_rent_period;
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