<?php

require_once('config.php');


try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($e->getMessage());
}



?>

<?php
// for the form

$form = <<<EOD
<form method="get" action="search_flats.php">
    <fieldset>
     <legend>Advanced Search Filters</legend>
     
     <div id="searchBox">
EOD;

?>

<?php


$location = ""; // %: means any thing
$price = 100000.0;
$num_of_bedrooms = " >= " . "0";
$num_of_bathrooms =  " >= " . "0";
$furnished = "0";
if (isset($_GET)) {

    if (!empty($_GET['location'])) {
        $location = $_GET['location'];
        $form .=  "<label>Location:</label> <input type='text' name='location' placeholder='Location' value='" . $location .  "'>";
    } else {
        $form .=  "<label>Location: </label> <input type='text' name='location' placeholder='Location' >";
    }
    // i will search where the price is less than or equals the given value
    if (!empty($_GET['price'])) {
        $price = (float)$_GET['price'];
        $form .= ' <label>Price: </label> <input type="number" min="0.00" max="10000.00" step="0.1" 
        name="price" placeholder="Less than a spcific price" value="' . $price . '"/> ';
    } else {
        $form .= '<label> Price:</label>  <input type="number" min="0.00" max="10000.00" step="0.1" 
        name="price" placeholder="Less than a spcific price" /> ';
    }

    if (!empty($_GET['num_of_bedrooms'])) {
        $num_of_bedrooms = " = " . $_GET['num_of_bedrooms'];
        $form .=  '<label> Number of bedrooms: </label> <input type="number" min="1" max="10" step="1" name="num_of_bedrooms" 
        placeholder="Exact #of bedrooms" value="' . $num_of_bedrooms .
            '"/>';
    } else {
        $form .= '<label>Number of bedrooms:</label>  <input type="number" min="1" max="10" step="1" name="num_of_bedrooms" 
        placeholder="Exact #of bedrooms" />';
    }

    if (!empty($_GET['num_of_bathrooms'])) {
        $num_of_bathrooms =  " = " . $_GET['num_of_bathrooms'];

        $form .= ' <label>Number of bathrooms:</label> <input type="number" min="1" max="4" step="1" 
        name="num_of_bathrooms" placeholder="Exact #of bathrooms" value="' . $num_of_bathrooms .
            '"/>';
    } else {
        $form .= ' <label>Number of bathrooms:</label> <input type="number" min="1" max="4" step="1" 
        name="num_of_bathrooms" placeholder="Exact #of bathrooms" />';
    }


    if (!empty($_GET['furnished'])) {
        $furnished = "1";

        $form .= ' <label>Furnished:</label> <input type="checkbox" name="furnished" value="1" checked>
        ';
    } else {
        $form .= ' <label>Furnished:</label> <input type="checkbox" name="furnished" value="1">';
    }
} else {

    $form .=  "<label>Location:</label> <input type='text' name='location' placeholder='Location' >";
    $form .= '<label>Price:</label>  <input type="number" min="0.00" max="10000.00" step="0.1" 
    name="price" placeholder="Less than a spcific price" /> ';
    $form .= '<label>Number of bedrooms:</label>  <input type="number" min="1" max="10" step="1" name="num_of_bedrooms" 
    placeholder="Exact #of bedrooms" />';
    $form .= '<label>Number of bathrooms:</label> <input type="number" min="1" max="4" step="1" 
        name="num_of_bathrooms" placeholder="Exact #of bathrooms" />';
    $form .= '<label>Furnished:</label> <input type="checkbox" name="furnished" value="1">';
}
$form .= '<button type="submit" class="input">Search</button> </div></fieldset></form>';

$sql = "Select id, cost_month, date_available, location, num_of_bedrooms from flat f "
    .
    " where location like '%"  . $location . "%' and cost_month <= " . $price . " and num_of_bedrooms  " . $num_of_bedrooms  . " and "
    .
    " num_of_bathrooms  " . $num_of_bathrooms  . " and furnished = " . $furnished . "
    and manager_status='approved' and f.id NOT IN ( SELECT flat_id FROM customer_rent_flat WHERE rent_end >= CURRENT_TIMESTAMP AND owner_accept = 1 )
    ;";





$table_search_result = <<<EOD
<table> <caption>Available Flats</caption>

    <thead>
            <tr>
                <th>id_referance number</th>
                <th>Cost per month</th>
                <th>Date available to rent</th>
                <th>location</th>
                <th >Number of bedrooms</th>
                <th ></th>
            </tr>
    </thead>

    <tbody>                        
EOD;


try {
    $result = $pdo->query($sql);
} catch (PDOException $e) {
    die($e->getMessage());
}

while ($row = $result->fetch()) {
    // if (!checkIfRented($row["id"], $pdo)) {
    $table_search_result .=  "<tr> <td>" . "<a href='./flat_details.php?" . "id=" . $row["id"] . "' target='_blank' class='flat_id_link'>" . $row["id"] . "</a></td>" .
        "<td>" . $row["cost_month"] . "</td>";
    $table_search_result .=      "<td >" . $row["date_available"] . "</td>"  . "<td>" . $row["location"] .  "</td>" . "<td>" . $row["num_of_bedrooms"] .  "</td>";

    $table_search_result .=  "<td >" . "<a href='./flat_details.php?" . "id=" . $row["id"] . "' target='_blank' class='flat_id_link'>" . "View Details" . "</a></td></tr>";
    //}
}


$table_search_result .= "</tbody> </table>";
?>

<html lang="en">

<head>
    <title>Search a Flat</title>
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
        <h1>Available Flats for Renting:</h1>
        <?php

        echo $form;
        echo  $table_search_result;



        ?>
    </main>
    <footer>
        <em>
            Birzeit, Palestine
            <a href="mailto:aws.ayyash.1@gmail.com"> E-mail</a> &copy; 2021.</em>
    </footer>
</body>

</html>