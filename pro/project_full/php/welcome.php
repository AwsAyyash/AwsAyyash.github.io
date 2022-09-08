
<?php


function getWelcomeNote()
{
    return "<div><h2 class='welcome'>Welcome '" . $_SESSION['user_type']  . "': \"" . $_SESSION['name'] . "\", UserName='" . $_SESSION['user_name'] . "', and YourID= '" . $_SESSION['user_id'] . "'</h2>" .
        "<p>You are already logged in, click <a href = '../index_proj.html'>here</a> to go to main page<br>" .
        "You are already logged in, to logout click <a href = './logout.php'>here</a>!</p></div>";
}
function noPermissionNote() // for owners
{

    return "<div><h2 class='welcome'>Welcome '" . $_SESSION['user_type']  . "': \"" . $_SESSION['name'] . "\", UserName='" . $_SESSION['user_name'] . "', and YourID= '" . $_SESSION['user_id'] . "'</h2>" .

        "<p>" . $_SESSION['user_type'] . " can not request preview, click <a href = '../index_proj.html'>here</a> to go to main page<br>" .
        "To logout click <a href = './logout.php'>here</a>!</p></div>";
}
?>