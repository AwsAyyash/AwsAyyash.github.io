<?php
$first_pic_flex_with_flat_data;
function display_marketing($result_market)
{
    $result_string = "<h2>Places around:</h2>  <ol>";

    while ($row_market = $result_market->fetch()) {

        $result_string .= '<li>
                                <h3 class="market_row">' . $row_market['palce_name'] . ': </h3>' . ' 
                                &#9; ' . $row_market['discreption'] . ', <a href ="' . $row_market['url']  . '"   
                                class="outside_mywebsite"
                                target="_blank" >view</a>
                            </li>';
    }

    $result_string .= '</ol>';
    return $result_string;
}


function display_pics($result_pic)
{
    $result_string = "<h2>Some pictures:</h2>  <ol>";

    $i = 0;
    while ($row_pic = $result_pic->fetch()) {

        // if ($i > 0) {
        if ($i % 2 == 0) {
            $result_string .= '<figure class="first_image">';
        } else {
            $result_string .= '<figure>';
        }
        $result_string .= '<img src="../images/flat_det_images/' . $row_pic['name'] . '" alt="' . $row_pic['name'] . '">
                <figcaption>' . $row_pic['name'] . '</figcaption>
                </figure>';;
        //}




        // global $first_pic_flex_with_flat_data = $first_pic_flex;

        $i++;
    }
    if ($i == 0)
        $result_string = "";
    else
        $result_string .= '</ol>';
    return $result_string;
}


function display_flat($row)
{

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

    $res_falt .= "<ul class ='flat_det col'>";
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

        "<div class='extra_features'>" .


        "<h2>Extra features:</h2>"  .
        "<li><em>Has car parking:</em> " . $has_car_parking . "</li>" .
        "<li><em>backyard:</em> " . $backyard . "</li>" .
        "<li><em>Has playground:</em> " . $has_playground . "</li>" .
        "<li><em>Has storage:</em> " . $has_storage . "</li>" .
        "</div>";

    $res_falt .= "</ul>";
    return $res_falt;
}


?>

<?php

require_once('config.php');

/*
 Display flat data
*/
$id = PHP_INT_MAX;
if (isset($_GET['id']) && !empty($_GET['id']))
    $id = $_GET['id'];

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    ///////check if valid, url may be changed by the user//////



    ////////


    if ($id != PHP_INT_MAX) // this to handle the invalid url changing
        $sql = "SELECT * FROM `flat` WHERE id= " . $id . " AND manager_status = 'approved'  and id NOT IN ( SELECT flat_id FROM customer_rent_flat WHERE rent_end >= CURRENT_TIMESTAMP )";
    else {
        $sql = "SELECT * FROM `flat` WHERE  manager_status = 'approved' and id NOT IN ( SELECT flat_id FROM customer_rent_flat WHERE rent_end >= CURRENT_TIMESTAMP )
         order by id ";
    }




    $result = $pdo->query($sql);
    $row = $result->fetch(); // this row has the flat we want to display

    if (empty($row['id']) || $row['manager_status'] != "approved") {


        $sql = "SELECT * FROM `flat` WHERE  manager_status = 'approved' and id NOT IN ( SELECT flat_id FROM customer_rent_flat WHERE rent_end >= CURRENT_TIMESTAMP ) order by id;";
        $result = $pdo->query($sql);
        $row = $result->fetch(); // this row has the flat we want to display



    }
    $id  = $row['id'];








    ///////////get the marketing info//////////    

    $sql_market  = "SELECT * FROM `marketing_info` WHERE flat_id = " . $id . ";";
    $result_market = $pdo->query($sql_market);

    $sql_pic  = "SELECT * FROM `picture` WHERE flat_id = " . $id . ";";
    $result_pic = $pdo->query($sql_pic); // i will get many rows

    $first_fetch_pic =  $result_pic->fetch();
    $first_pic_flex_with_flat_data = '<img src="../images/flat_det_images/' .
        $first_fetch_pic['name'] . '" alt="' . $first_fetch_pic['name'] . '">';

    /////////////////////

    $pdo = null;
} catch (PDOException $e) {
    die($e->getMessage());
}


?>

<html lang="en">

<head>
    <title>Details for Flat#<?php echo $id ?></title>
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
        <div class="multi_column">
            <div class="col">
                <h1>Details for the Flat with referance number = <?php echo $id ?> </h1>


                <div class="multi_column">
                    <?php

                    echo display_flat($row);

                    ?>

                    <div class="col">
                        <!-- put one img here ,,,,, and the rest below 

                        <img src="../images/flat1.jpg" alt="">-->
                        <?php echo  $first_pic_flex_with_flat_data; ?>
                    </div>
                </div>

                <div>
                    <?php echo display_pics($result_pic); ?>

                </div>




            </div>

            <aside class="det">
                <?php echo display_marketing($result_market); ?>

                <hr>
                <nav class="aside_nav">
                    <ul id="not_main_nav">
                        <li>

                            <?php
                            echo  '- <a href="./rent.php?id=' . $id . '" target="_blank">Rent The Flat.</a>'

                            ?>
                        </li>
                        <li>
                            <?php
                            echo  '- <a href="./request_preview.php?id=' . $id . '"  target="_blank">Request Flat Preview Appointment.</a>'

                            ?>
                        </li>
                    </ul>
                </nav>
            </aside>

        </div>

    </main>





    <footer>
        <em>
            Birzeit, Palestine
            <a href="mailto:aws.ayyash.1@gmail.com"> E-mail</a> &copy; 2021.</em>
    </footer>
</body>

</html>