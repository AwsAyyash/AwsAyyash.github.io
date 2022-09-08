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

function isValidUserName($pdo, $user_name)
{

    $sql = "SELECT COUNT(*) AS counter_  FROM `users` "

        . " WHERE user_name = '" . $user_name . "';";
    $result1 = $pdo->query($sql);
    $row = $result1->fetch();
    $counter = $row['counter_'];
    if ($counter > 0) {
        return false;
    }
    return true;
}


$form0_user_type = <<<EOD

        <form method="post" action="./register.php">
            <fieldset>
                <legend>Register new account-step#0</legend>
                
                

                <div class = "form_line">
                    <label for="user_type">User type: </label>
                    <select name="user_type">
                        <option value="owner">Owner</option>
                        <option value="customer">Customer</option>
                    
                    </select>
                </div>

                <button type ="submit">next</button>

            </fieldset>
        </form>
    
EOD;

$form1_customer = <<<EOD
<h3 class="warning">Red Input=required</h3>
<form method="post" action="./register.php">
    <fieldset>
     <legend>Register new account-step#1</legend>
     
     <div>

     

     <div class = "form_line">
     <label for="id_card">National ID:</label>
      <input type='text' name='id_card' placeholder='Enter 9 digits number'  pattern="^\\d{9}$" required>
     </div>
     
     
     <div class = "form_line">
     <label for="name">Name:</label>

     <input type='text' name='name' placeholder='Enter your full name'  pattern="^[a-zA-Z ]*$" required>
     </div>
     
     <h3>Address</h3>
     
     <div class = "form_line address_row">
     <label for="house_number">House number:</label>

      <input type='text' name='house_number' placeholder='Enter your house#'  pattern="\d+" required>
     </div>
      
     <div class = "form_line address_row">
     <label for="street_name">Street name:</label>

      <input type='text' name='street_name' placeholder='Enter your street name' required>
     </div>

     <div class = "form_line address_row">
     <label for="city">City:</label>

      <input type='text' name='city' placeholder='Enter your city name' required>

     </div>
      
     
     <div class = "form_line address_row">

     <label for="postal_code">Postal code:</label>

      <input type='text' name='postal_code' placeholder='Enter your city postal code' required>

     </div>

       
    
     <div class = "form_line">
     <label for="date_of_birth">Date of Birth:</label>

      <input type='date' name='date_of_birth' placeholder="dd-mm-yyyy" value=""
      min="1900-01-01" required>

     </div>

     <div class = "form_line">
     <label for="email">E-mail address:</label>

      <input type='email' name='email' placeholder="Enter your e-mail address" required>

     </div>

     <div class = "form_line">
     <label for="mobile">Mobile Number:</label>

      <input type='text' name='mobile' placeholder='Enter 10 digits mobile number'  pattern="^\\d{10}$" required>
     </div>

     <div class = "form_line">
     <label for="telephone">Telephone Number:</label>

       <input type='text' name='telephone' placeholder='Enter 9 digits telephone'  pattern="^\\d{9}$" required>
     </div>
     <button type ="submit">next</button>

    </div>

    </fieldset>
    </form>
EOD;



$form1_owner = <<<EOD
<h3 class="warning">Red Input=required</h3>
<form method="post" action="./register.php">
    <fieldset>
     <legend>Register new account-step#1</legend>
     
     <div>

     

     <div class = "form_line">
     <label for="id_card">National ID:</label>
      <input type='text' name='id_card' placeholder='Enter 9 digits number'  pattern="^\\d{9}$" required>
     </div>
     
     
     <div class = "form_line">
     <label for="name">Name:</label>

     <input type='text' name='name' placeholder='Enter your full name'  pattern="^[a-zA-Z ]*$" required>
     </div>
     
     <h3>Address</h3>
     
     <div class = "form_line address_row">
     <label for="house_number">House number:</label>

      <input type='text' name='house_number' placeholder='Enter your house#'  pattern="\d+" required>
     </div>
      
     <div class = "form_line address_row">
     <label for="street_name">Street name:</label>

      <input type='text' name='street_name' placeholder='Enter your street name' required>
     </div>

     <div class = "form_line address_row">
     <label for="city">City:</label>

      <input type='text' name='city' placeholder='Enter your city name' required>

     </div>
      
     
     <div class = "form_line address_row">

     <label for="postal_code">Postal code:</label>

      <input type='text' name='postal_code' placeholder='Enter your city postal code' required>

     </div>

       
    
     <div class = "form_line">
     <label for="date_of_birth">Date of Birth:</label>

      <input type='date' name='date_of_birth' placeholder="dd-mm-yyyy" value=""
      min="1900-01-01" required>

     </div>

     <div class = "form_line">
     <label for="email">E-mail address:</label>

      <input type='email' name='email' placeholder="Enter your e-mail address" required>

     </div>

     <div class = "form_line">
     <label for="mobile">Mobile Number:</label>

      <input type='text' name='mobile' placeholder='Enter 10 digits mobile number'  pattern="^\\d{10}$" required> 
     </div>

     <div class = "form_line">
     <label for="telephone">Telephone Number:</label>

       <input type='text' name='telephone' placeholder='Enter 9 digits telephone'  pattern="^\\d{9}$" required>
     </div>



     
     <div class = "form_line">
     <label for="bank_name">Bank Name:</label>

       <input type='text' name='bank_name' placeholder='Enter your bank name'  required>
     </div>


     <div class = "form_line">
     <label for="bank_branch">Bank Branch:</label>

       <input type='text' name='bank_branch' placeholder='Enter your bank branch'  required>
     </div>

     <div class = "form_line">
     <label for="account_number">Account Number:</label>

       <input type='text' name='account_number' placeholder='Enter your bank account#'  required>
     </div>

     <div class = "form_line">
     <label for="b_postal_code">Bank postal code:</label>

       <input type='text' name='b_postal_code' placeholder='Enter your bank postal code'  required>
     </div>




     <button type ="submit">next</button>

    </div>

    </fieldset>
    </form>
EOD;





$form2 = <<<EOD
<h3 class="warning">Red Input=required</h3>
<form method="post" action="./register.php">
    <fieldset>
     <legend>Register new account-step#2</legend>
     
     <div>

     
     <div class = "form_line">
     <label for="user_name">User Name:</label>
      <input type='text' name='user_name' placeholder='Choose a user name'  pattern="^([a-zA-Z0-9_-*]){3,20}$" required>
      <label for="user_name" class='warning'>must be: 3-20 characters</label>
     </div>

     <div class = "form_line">
     <label for="password">Password:</label>
      <input type='password' name='password' placeholder='Enter password'  pattern="^[0-9]([a-zA-Z0-9_-]{4,13})[a-z]$" required>
      <label for="user_name" class='warning'>must be: 6-15 characters. Should start with a digit and ends with a lower case alphabet</label>

      </div>

     <div class = "form_line">
     <label for="confirm_password">Confirm Password:</label>
      <input type='password' name='confirm_password' placeholder='Confirm your password'  pattern="^[0-9]([a-zA-Z0-9_-]{4,13})[a-z]$" required>
     </div>
     
     
     
     
     
     <button type ="submit">next</button>

    </div>
    </fieldset>
    </form>
EOD;

$form3_confirm = <<<EDO
            <form method="post" action="./register.php">
                <fieldset>
                    <legend>Register new account-step#3</legend>
                    <h2>Are you sure that all the data are completed? <input type="checkbox" name="ok" value="1"></h2>
                    <button type ="submit">Confirm</button>

                </fieldset>
            </form>


EDO;




function register_account($pdo)
{

    $sql_insert_user = "INSERT INTO 
    `users`( `user_name`, `password`, `id_card`, `name`, `date_of_birth`, `email`, `mobile`, `telephone`, `user_type`)
     VALUES
     ( '" . $_SESSION['user_name'] .
        "' , '" . $_SESSION['password'] .
        "' , " . $_SESSION['id_card'] .
        " , '" . $_SESSION['name'] .
        "'  , '" . $_SESSION['date_of_birth'] .
        "'  , '" . $_SESSION['email'] .
        "' , '" . $_SESSION['mobile'] .
        "'  , '" . $_SESSION['telephone'] .
        "' , '" . $_SESSION['user_type'] . "' )";

    $pdo->query($sql_insert_user);
    $id_of_user_last_inserted =  $pdo->lastInsertId();



    $sql_insert_address = "INSERT INTO `address_users`(`house_number`, `street_name`, `city`, `postal_code`, `user_id`)
     VALUES 
     (" . $_SESSION['house_number'] .
        " , '" . $_SESSION['street_name'] .
        "' , '" . $_SESSION['city'] .
        "' , " . $_SESSION['postal_code'] .
        "  , " . $id_of_user_last_inserted . " )";

    $pdo->query($sql_insert_address);



    //$id_of_user_last_inserted =  $pdo->lastInsertId();




    $result_string = "<h2>";
    if ($_SESSION['user_type'] == "owner") {
        $result_string = "<h2 class='warning'>Dear owner " . $_SESSION['name'] . " your registration is done. and your owner_id is '" .
            $id_of_user_last_inserted . "', please save it.</h2>";

        $sql_insert_owner_bank = "INSERT INTO `owner_bank`( `bank_name`, `bank_branch`, `account_number`, `postal_code`, `user_id`)
         VALUES 
         ( '" . $_SESSION['bank_name'] .
            "' , '" . $_SESSION['bank_branch'] .
            "' , " . $_SESSION['account_number'] .
            " , " . $_SESSION['b_postal_code'] .
            "  , " . $id_of_user_last_inserted .

            " )";

        $pdo->query($sql_insert_owner_bank);
    } else {
        $result_string = "Dear customer " . $_SESSION['name'] . " your registration is done. your customer_id is '" .
            $id_of_user_last_inserted . "', please save it.";
    }
    $result_string .= "</h2>";

    unset($_POST);

    session_unset();
    session_destroy();
    $id_of_user_last_inserted = null;
    return $result_string;
}


?>




<html lang="en">

<head>
    <title>Register</title>
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
        <h1>Register New Account</h1>
        <?php
        if (isset($_SESSION)  && isset($_SESSION['logged_in'])   && $_SESSION['logged_in'] == "true") {
            require_once('welcome.php');
            echo getWelcomeNote();
        } else {
            if ($_SERVER["REQUEST_METHOD"] == "POST" && ((isset($_POST['user_type']) && !empty($_POST['user_type'])) || (isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])))) {


                if (isset($_POST['ok']) && !empty($_POST['ok'])) {
                    if ($_POST['ok'] == "1") {
                        echo register_account($pdo);
                    } else {
                        session_unset();
                        session_destroy();
                        echo $form0_user_type; // i should reset the _session here, anyways
                    }
                } else if (isset($_POST['user_name']) && !empty($_POST['user_name'])) {



                    if (isValidUserName($pdo, $_POST['user_name'])) {
                        $_SESSION['user_name'] = $_POST['user_name'];
                        if (isset($_POST['password']) && isset($_POST['confirm_password']) && $_POST['password'] == $_POST['confirm_password']) {
                            $_SESSION['password'] = $_POST['password'];


                            echo $form3_confirm;
                        } else {
                            echo "<h2 class='warning'>* Confirmed password does not match</h2>";
                            echo $form2;
                        }
                    } else {
                        echo "<h2 class='warning'>* User name, exists! try another one</h2>";
                        echo $form2;
                    }
                } else if (isset($_POST['name']) && !empty($_POST['name'])) {

                    $_SESSION['id_card'] = $_POST['id_card'];

                    $_SESSION['name'] = $_POST['name'];
                    $_SESSION['house_number'] = $_POST['house_number'];

                    $_SESSION['street_name'] = $_POST['street_name'];
                    $_SESSION['city'] = $_POST['city'];
                    $_SESSION['postal_code'] = $_POST['postal_code'];
                    $_SESSION['date_of_birth'] = $_POST['date_of_birth'];

                    $_SESSION['email'] = $_POST['email'];
                    $_SESSION['mobile'] = $_POST['mobile'];
                    $_SESSION['telephone'] = $_POST['telephone'];

                    if ($_SESSION['user_type'] == "owner") {

                        $_SESSION['bank_name'] = $_POST['bank_name'];

                        $_SESSION['bank_branch'] = $_POST['bank_branch'];
                        $_SESSION['account_number'] = $_POST['account_number'];
                        $_SESSION['b_postal_code'] = $_POST['b_postal_code'];
                    }

                    echo $form2;
                } else if (isset($_POST['user_type']) && !empty($_POST['user_type'])) {

                    $_SESSION['user_type'] = $_POST['user_type'];

                    if ($_SESSION['user_type'] == "owner")
                        echo $form1_owner;
                    else
                        echo $form1_customer;
                }
            } else {
                echo $form0_user_type;
            }
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