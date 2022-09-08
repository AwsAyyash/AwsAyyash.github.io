<?php
session_start();

//if (isset($_SESSION['usernamee'])) {
//setcookie('username', '', time() - 3600);

//unset($_SESSION['usernamee']);
session_unset();
session_destroy();
//}


?>
<html>

<head>
</head>

<body>
    Good Bye and Come back <br />
    <a href="./lab16-exercise01-session.php"> Log In </a>
</body>

</html>