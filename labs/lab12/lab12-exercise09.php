<?php
/*
 Outputs the relevant bootstrap markup to display the weather forcast 
 for a single day
*/
function outputForecast($day, $high, $low)
{
   echo '<div class="panel panel-default col-lg-3 col-md-3 col-sm-6">';
   echo '<div class="panel-heading">';
   echo '<h3 class="panel-title">' . $day . '</h3>';
   echo '</div>';
   echo '<div class="panel-body">';
   echo '<table class="table table-hover">';
   echo '<tr><td>High:</td><td>' . $high . '</td></tr>';
   echo '<tr><td>Low:</td><td>' . $low . '</td></tr>';
   echo '</table>';
   echo '</div>';
   echo '</div>';
}
?>

<html lang="en">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <title>Exercise 12-9 Processing file uploads</title>

   <!-- Latest compiled and minified Bootstrap Core CSS -->
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
      <h1>File access in PHP</h1>
   </header>

   <div class='container'>
      <?php
      //start by echoing the data in the file
      $fileContents = file_get_contents("datafile.txt");
      $daysOfWeek = explode("\n", $fileContents);
      //print_r($daysOfWeek);

      foreach ($daysOfWeek as $row) {
         $elements = explode(",", $row);

         $day = $elements[1] . ' ' . $elements[0] . ',' . $elements[2];
         $high  = $elements[3];
         $low  = $elements[4];
         outputForecast($day, $high, $low);
      }

      ?>
   </div>


</body>

</html>