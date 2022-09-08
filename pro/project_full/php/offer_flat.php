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
function set_into_session_0()
{

    $_SESSION['cost_month'] = $_POST['cost_month'];
    $_SESSION['date_available'] = $_POST['date_available'];
    $_SESSION['location'] = $_POST['location'];
    $_SESSION['num_of_bedrooms'] = $_POST['num_of_bedrooms'];
    $_SESSION['num_of_bathrooms'] = $_POST['num_of_bathrooms'];
    $_SESSION['rent_condition'] = $_POST['rent_condition'];
    $_SESSION['size'] = $_POST['size'];
    $_SESSION['furnished'] = $_POST['furnished'];
    $_SESSION['has_heating_system'] = $_POST['has_heating_system'];
    $_SESSION['has_air_condition'] = $_POST['has_air_condition'];
    $_SESSION['has_access_control'] = $_POST['has_access_control'];
    $_SESSION['has_car_parking'] = $_POST['has_car_parking'];
    $_SESSION['backyard'] = $_POST['backyard'];
    $_SESSION['has_playground'] = $_POST['has_playground'];
    $_SESSION['has_storage'] = $_POST['has_storage'];
    $_SESSION['about_location'] = $_POST['about_location'];
}
function insert_flat($pdo)
{
    $sql_ins_flat = "INSERT INTO `flat`
  ( `cost_month`, `date_available`, `location`, `num_of_bedrooms`, `num_of_bathrooms`,
   `rent_condition`, `size`, `furnished`, `has_heating_system`, `has_air_condition`, `has_access_control`, 
   `has_car_parking`, `backyard`, `has_playground`, `has_storage`, `about_location`, `user_id`) 
    VALUES ( '" . $_SESSION['cost_month'] .
        "' , '" . $_SESSION['date_available'] .
        "' , '" . $_SESSION['location'] .
        "' , '" . $_SESSION['num_of_bedrooms'] .
        "'  , '" . $_SESSION['num_of_bathrooms'] .
        "'  , '" . $_SESSION['rent_condition'] .
        "' , '" . $_SESSION['size'] .
        "'  , '" . $_SESSION['furnished'] .
        "' , '" . $_SESSION['has_heating_system'] .
        "' , '" . $_SESSION['has_air_condition'] .
        "' , '" . $_SESSION['has_access_control'] .
        "' , '" . $_SESSION['has_car_parking'] .
        "' , '" . $_SESSION['backyard'] .
        "' , '" . $_SESSION['has_playground'] .
        "' , '" . $_SESSION['has_storage'] .
        "' , '" . $_SESSION['about_location'] .
        "' , '" . $_SESSION['user_id'] .
        "' )";


    try {

        $pdo->query($sql_ins_flat);
        $id_of_flat_last_inserted =  $pdo->lastInsertId();

        $_SESSION['id_of_flat_last_inserted'] = $id_of_flat_last_inserted;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

function is_valid_3_files()
{
    $total = count($_FILES['file1']['tmp_name']);
    if ($total < 3) {
        return false;
    }
    return true;
}

function process_pictures($pdo)
{
    $count = 0;
    $i = 1;
    // loop through each uploaded file
    foreach ($_FILES["file1"]["error"] as $error) {
        if ($error == UPLOAD_ERR_OK) {
            $clientName = $_FILES["file1"]["name"][$count];
            $serverName = $_FILES["file1"]["tmp_name"][$count];
            $fileType = $_FILES["file1"]["type"][$count];
            moveFile($serverName, $clientName, $fileType, $i, $pdo);
            $count++;
            $i++;
        }
    }
}

function insert_pictures_database($flat_id, $id_of_flat_p_num, $pdo)
{

    $sql_ist_picture =  "INSERT INTO `picture`(`name`, `flat_id`) VALUES ('" . $id_of_flat_p_num . "', " . $flat_id .  ")";

    $pdo->query($sql_ist_picture);
}
function getFileUploadForm()
{
    return '
    <h3 class="warning">Red Input=required</h3>
    <form enctype="multipart/form-data" method="post"  action ="./offer_flat.php">
             <div>
               <label for="file1">Upload a file</label>
               <input type="file" name="file1[]" id="file1" multiple required/>
               <p>Browse the pictures (at least 3).</p>
            </div>
            <input type="submit" />
            </form>';
}


$num_of_marketing_places_form = <<<EDO
            <form method="post" action="./offer_flat.php">
                <fieldset>
                    <legend>Marketing places</legend>
                    <label>(Optional) you can add as many near marketing places:</label> <input type="number" min="0" name="num_of_marketing_places" step='1'  placeholder='Number of marketing places'>
                    <button type ="submit">Next</button>

                </fieldset>
            </form>
EDO;

$num_of_days_time_table_form = <<<EDO
            <h3 class='warning'>Red Input=required</h3>
            <form method="post" action="./offer_flat.php">
                <fieldset>
                    <legend>add the number of days that are available for preview</legend>
                    <label>(mandatory) Set the number of days:</label> <input type="number" min="1" name="num_of_days_preview" step='1'  placeholder='Number of days' required></h2>
                    <button type ="submit">Next</button>

                </fieldset>
            </form>
EDO;

function time_table_form()
{


    $time_table_form = <<<EDO

            <div class='form_line'>
                
                <select name='days[]'>
                    <option value='saturday'>Saturday</option>
                    <option value='sunday'>Sunday</option>   
                    <option value='monday'>Monday</option>   
                    <option value='tuesday'>Tuesday</option>             
                    <option value='wednesday'>Wednesday</option>             
                    <option value='thursday'>Thursday</option>             
                    <option value='friday'>Friday</option>             
          
            
                </select>
                    Start time: <input type="time" name="time_start[]" required> 
                    End time: <input type="time" name="time_end[]" required>
                    Telephone: <input type='text' name='telephone[]'  placeholder='telephone' required>
                
            </div>
EDO;
    return $time_table_form;
}


function display_time_table_forms($i)
{

    echo '<h3 class="warning">Red Input=required</h3>
    <form method="post" action="./offer_flat.php">
            <fieldset>
        <legend>Preview time table</legend>
        <h2>You have to put a time for previewing the flat:</h2> ';
    for ($j = 0; $j < $i; $j++) {
        echo '<h3 class="warning">Day ' . $j + 1 . ': </h3>';
        echo time_table_form();
    }

    echo '
    
    <button type ="submit">Submit</button>

    </fieldset>
</form>
    ';
}




function display_marketing_places_form($i)
{

    echo ' <h3 class="warning">Red Input=required</h3>
    <form  method="post"  action ="./offer_flat.php">
               
    <h2>Add nearby Schools, Universities, Malls, ... </h2>';
    for ($j = 0; $j < $i; $j++) {
        echo '<h3 class="warning">Place ' . $j + 1 . ': </h3>';
        echo marketing_places_form();
    }

    echo '
    
    <input type="submit" />
            </form>
    ';
}
function marketing_places_form()
{
    return '
                
                <div class = "form_line">
                    <label for="place_name">Place name:</label>
                    <input type="text" name="place_name[]"  placeholder="Place name" required>
                </div>

                <div class = "form_line">
                    <label for="discreption">Discreption:</label>
                    <input type="text" name="discreption[]"  placeholder="discreption" required>
                </div>

                <div class = "form_line">
                    <label for="url">(URL) link for the place (if exists):</label>
                    <input type="text" name="url[]"  placeholder="Link for sites" >
                </div>
                
                ';
}


function print_success_flat_offer()
{

    echo "<h2 class='welcome'>Dear '" . $_SESSION['user_type']  . "', " . $_SESSION['user_name'] . ", Your ID= " . $_SESSION['user_id'] . "</h2>";
    echo "<h3>Your flat has been added successfully, click <a href = '../index_proj.html'>here</a> to go to main page</h3>";
    echo "<h3>You are already logged in, to logout click <a href = './logout.php'>here</a>!</h3>";
    echo '<h3 class="warning">Once the manager accept it, you will be notified!</h3>';
}

function add_marketing_info_database($pdo)
{
    $place_name = $_POST['place_name']; // i get an array
    $discreption = $_POST['discreption'];
    $url = $_POST['url'];

    $flat_id_for_marketing =  $_SESSION['id_of_flat_last_inserted'];
    foreach ($place_name as $key => $value) {
        $sql_insert_marketing = "INSERT INTO `marketing_info`(`palce_name`, `discreption`, `url`, `flat_id`) 
        VALUES ('" . $value . "','" . $discreption[$key] . "','" . $url[$key] . "','" . $flat_id_for_marketing . "')";

        $pdo->exec($sql_insert_marketing);
    }
}

function add_time_table_to_database($pdo)
{

    $days = $_POST['days']; // i get an array
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];
    $telephone = $_POST['telephone'];

    $flat_id_for_time_preview =  $_SESSION['id_of_flat_last_inserted'];


    foreach ($days as $key => $value) {
        if ($time_start[$key] != "" && $time_end[$key] != ""  && $value != "" && $telephone[$key] != "") {
            $sql_insert_time_preview = "INSERT INTO `preview_time_table`(`day`, `time_start`, `time_end`, `telephone`, `flat_id`)  
            VALUES ('" . $value . "','" . $time_start[$key] . "','" . $time_end[$key] . "','" . $telephone[$key] . "','"
                . $flat_id_for_time_preview . "')";

            $pdo->exec($sql_insert_time_preview);
        }
    }
}

function moveFile($fileToMove, $destination, $fileType, $i, $pdo)
{

    $validExt = array("jpg", "png");
    $validMime = array("image/jpeg", "image/png");
    // make an array of two elements, first=filename before extension,
    // and the second=extension
    $components = explode(".", $destination);
    // retrieve just the end component (i.e., the extension)
    $extension = end($components);
    // check to see if file type is a valid one
    if (in_array($fileType, $validMime) && in_array($extension, $validExt)) {
        move_uploaded_file($fileToMove, '../images/flat_det_images/' . $_SESSION['id_of_flat_last_inserted'] . '_p' .
            $i . '.' . $extension) or
            die("error");
        insert_pictures_database($_SESSION['id_of_flat_last_inserted'], $_SESSION['id_of_flat_last_inserted'] . '_p' . $i . '.' . $extension, $pdo);
    } else
        echo 'Invalid file type=' . $fileType . ' Extension=' . $extension .
            '<br/>';
}

$form_data_initial_1 = <<<EOD
<h3 class="warning">Red Input=required</h3>
<form method="post" action="./offer_flat.php">
    <fieldset>
     <legend>Offer your available flat for renting</legend>
     <div>


    <div class = 'form_line'>
        <label for='cost_month'>Cost per month:</label>
        <input type='number' min='10' max='10000' step='1' name='cost_month' placeholder='Cost per month' required/>

     </div>

    <div class = 'form_line'>
        <label for='date_available'>Available from:</label>
        <input type='date' name='date_available'  placeholder='dd-mm-yyyy'  required>
     </div> 

    <div class = 'form_line'>
     <label for='location'>Location:</label>
     <input type='text' name='location'  placeholder='Location' required>
    </div>

    <div class = 'form_line'>
     <label for='num_of_bedrooms'>Number of bedrooms:</label>
     <input type='number' min='1' max='10' step='1' name='num_of_bedrooms' placeholder='bedrooms' required/>
    </div>

    <div class = 'form_line'>
     <label for='num_of_bathrooms'>Number of bathrooms:</label>
     <input type='number' min='1' max='10' step='1' name='num_of_bathrooms' placeholder='bathrooms'  required/>
    </div>


    <div class = 'form_line'>
     <label for='rent_condition'>Rent conditions:</label>
     <input type='text' name='rent_condition'  placeholder='Rent conditions' required>
    </div>


    <div class = 'form_line'>
     <label for='size'>Size:</label>
     <input type='number' min='50' max='200' step='1' name='size' placeholder='Size in squred meter' required/>
    </div>

    <div class = 'form_line'>
     <label for='furnished'>Furnished:</label>

     <select name='furnished'>
            <option value='1'>Yes</option>
            <option value='0'>No</option>
                    
    </select>
    </div> 

    <div class = 'form_line'>
     <label for='has_heating_system'>Has heating system:</label>

     <select name='has_heating_system'>
            <option value='1'>Yes</option>
            <option value='0'>No</option>             
    </select>
    </div>


    <div class = 'form_line'>
    <label for='has_air_condition'>Has air condition:</label>

    <select name='has_air_condition'>
           <option value='1'>Yes</option>
           <option value='0'>No</option>             
   </select>
   </div>

    <div class = 'form_line'>
    <label for='has_access_control'>Has access control:</label>

    <select name='has_access_control'>
           <option value='1'>Yes</option>
           <option value='0'>No</option>             
   </select>
   </div>

   <div class = 'form_line'>
   <label for='about_location'>About the location:</label>
   <input type='text' name='about_location'  placeholder='About the location' required>
  </div>

   <div class='extra_features'>
    <h2>Extra features:</h2>

    <div class = 'form_line'>
    <label for='has_car_parking'>Has car parking:</label>

    <select name='has_car_parking'>
           <option value='1'>Yes</option>
           <option value='0'>No</option>             
   </select>
   </div>

    <div class = 'form_line'>
    <label for='backyard'>Backyard:</label>

    <select name='backyard'>
           <option value='individual'>individual</option>
           <option value='shared'>shared</option>   
           <option value='none'>No backyard</option>             
          
   </select>
   </div>


    <div class = 'form_line'>
    <label for='has_playground'>Has playground:</label>

    <select name='has_playground'>
           <option value='1'>Yes</option>
           <option value='0'>No</option>             
   </select>
   </div>

    <div class = 'form_line'>
    <label for='has_storage'>Has storage:</label>

    <select name='has_storage'>
           <option value='1'>Yes</option>
           <option value='0'>No</option>             
   </select>
   </div>

   </div>
   

   <div class = 'form_line'>

    <input type="submit" > 
                </div>
            </div>
        </fieldset>
    </form>
EOD;

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

        <?php

        if (isset($_SESSION)  && isset($_SESSION['logged_in'])   && $_SESSION['logged_in'] == "true") {

            if ($_SESSION['user_type'] == "owner" || $_SESSION['user_type'] == "manager") {


                ////////////////////////////////////////////////////////////////////////////////////////////



                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    if (isset($_POST['days']) && !empty($_POST['days'])) {

                        // handle the time table info here! by calling a function : => then add it to the data base

                        add_time_table_to_database($pdo);

                        // print that the flat is done ok
                        print_success_flat_offer();


                        // here i should notify the manager; // me aws: after i have completed this => do not make any thing,
                        // just display where the 'pending' flats to a manager;


                    } else if (isset($_POST['num_of_days_preview'])) {

                        if (!empty($_POST['num_of_days_preview']))
                            display_time_table_forms($_POST['num_of_days_preview']);
                        else
                            echo $num_of_days_time_table_form;
                    } else  if (isset($_POST['place_name']) && !empty($_POST['place_name'])) {
                        // handle the marketing info here! by calling a function :
                        add_marketing_info_database($pdo);

                        // here add the time-table form
                        echo $num_of_days_time_table_form;
                    } else if (isset($_POST['num_of_marketing_places']) /* && !empty($_POST['num_of_marketing_places']) */) {
                        // display the form of marketing places
                        if ((int)$_POST['num_of_marketing_places'] > 0) {
                            display_marketing_places_form($_POST['num_of_marketing_places']);
                        } else {
                            // here add the time-table form.
                            echo $num_of_days_time_table_form;
                        }
                    } else if (isset($_FILES['file1']["tmp_name"]) && !empty($_FILES["file1"]["tmp_name"])) {

                        if (is_valid_3_files()) {

                            process_pictures($pdo); // add to the server, + add to the 'database'!;

                            // show here the next for "marketing"
                            echo $num_of_marketing_places_form;
                            //echo marketing_places_form();
                            //  $_SESSION['step'] = "2";
                        } else {

                            echo "<h3 class='warning'>Number of pictures must be at least 3!</h3>";
                            echo  getFileUploadForm();
                        }

                        //$_SESSION['step'] = "1";
                    } else if (isset($_POST['cost_month']) && !empty($_POST['cost_month'])) {
                        // set_into_session_1();
                        // check if valid 3 files?
                        set_into_session_0();
                        insert_flat($pdo);
                        echo  getFileUploadForm(); // for images

                    }
                } else {


                    echo $form_data_initial_1;
                }

                ///////////////////////////////////////////////////////////////////////////////////////////////



            } else {
                echo "<h2 class='welcome'>Welcome dear customer " . $_SESSION['user_name'] . "</h2>";
                echo "<h3>Customers can not offer flats, click <a href = '../index_proj.html'>here</a> to go to main page</h3>";
                echo "<h3>To logout click <a href = './logout.php'>here</a>!</h3>";
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