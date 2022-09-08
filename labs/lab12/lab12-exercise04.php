<?php

function displayPostStatus()
{
   if (!isset($_POST['alias']))
      echo 'NO post detected';
   else if (isset($_POST['alias']) && $_POST['alias'] != "") {
      echo "Hello " . $_POST['alias'];
   } else {
      echo "Post detected, but no value";
   }
}

?>
<html lang="en">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <title>Exercise 12-4 Detecting a Post</title>

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
   </header>



   <div class="container theme-showcase" role="main">
      <div class="jumbotron">
         <h1><?php displayPostStatus(); ?></h1>
      </div>
      <form action='' method='post'>
         Enter Something:
         <input type='text' name='alias' />
         <input type='submit' value='Post' />
      </form>
   </div>
</body>

</html>