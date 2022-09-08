<?php

/*
   $content holds a 2D array with keys being menu names and values being an array 
   with a subtitle, and content
*/
$content = array(
   "Home" => array("Home Page", "This page is the home page"),
   "Page 2" => array("The second page", "second page content here"),
   "Page 3" => array("The third page", "third page content here"),
   "Page 4" => array("The fourth page", "fourth page content here")
);

$justContent = array_values($content);

?>
<html lang="en">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

   <!-- Latest compiled and minified Bootstrap Core CSS -->
   <title>

      <?php

      if (isset($_GET['page']))
         echo $justContent[$_GET['page'] - 1][0];

      ?>
   </title>
   <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
   <header>
      <nav>
         <ul>
            <li>
               <p><a href="./index.html">Lab12 home page</a></p>
            </li>
         </ul>
      </nav>
      <h1>Demonstrating $_GET usage</h1>
   </header>




   <?php
   echo '<ul class="nav nav-pills">';
   $pageCount = 1;
   foreach ($content as $key => $elements) {
      echo "<li ";
      if ($pageCount == $_GET['page'])
         echo " class='active'";
      else
         echo "";
      echo "><a href='?page=$pageCount'>$key</a></li>";
      $pageCount++;
   }

   echo '</ul>';

   echo $justContent[$_GET['page'] - 1][1];
   ?>


</body>

</html>