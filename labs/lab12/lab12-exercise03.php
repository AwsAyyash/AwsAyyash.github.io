<html lang="en">

<head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>Exercise 12-3 Sorting Arrays</title>

      <!-- Latest compiled and minified Bootstrap Core CSS -->
      <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

      <style>
            tr {
                  border-bottom: 1pt solid rgb(112, 112, 112);
            }

            tr:nth-child(odd) {
                  background: #FFF
            }



            td {
                  padding: 10px 0;
                  padding-right: 470px;
            }
      </style>
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
                  <h1>Division Leaderboard</h1>
                  <p>Sports League</p>
            </div>

            <?php
            function print_table($arr)
            {
                  $str = '<table>';
                  $str .= '<tr> <th>Name</th> <th>Score</th> </tr>';
                  foreach ($arr as $key => $score) {
                        $str .=  '<tr> <td>' . $key . '</td> <td>' . $score . '</td></tr>';
                        //$str .= '<hr />';
                  }
                  $str .= '</table>';
                  return $str;
            }
            ?>
            <?php

            $players = array(
                  "Jhan Belig" => 189,
                  "Yemenev Baltroy" => 367,
                  "Ilroy Malvi" => 210,
                  "James John" => 121,
                  "Walton Ling" => 368,
                  "Mitch Moore" => 382,
                  "Urslaw Whig" => 422,
                  "Leo M. Toalde" => 192,
                  "Richard Bee" => 281,
                  "Travis Wise" => 182
            );
            echo "<pre>";
            // print_r($players);
            asort($players);
            $players = array_reverse($players);
            echo print_table($players);
            //print_r($players);
            echo "</pre>";
            ?>
      </div>
</body>

</html>