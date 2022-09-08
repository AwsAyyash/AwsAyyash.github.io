<?php
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600);

    unset($_COOKIE['username']);
}


?>
<html>

<head>
</head>

<body>
    Good Bye and Come back <br />
    <a href="./lab16-exercise01-cookie.php"> Log In </a>
</body>

</html>