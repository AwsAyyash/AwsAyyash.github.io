<?php


?>
<!DOCTYPE html>
<html>

<head>
    <title>Error</title>



    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <header>
        <nav class="multi_column">
            <div class="col">
                <a href="./index_proj.html"><img src="../images/logo.jpg" alt="logo" title="company logo" /></a>
                <a href="./index_proj.html" class="inside_main_nav" id="company_name">Birzeit flat rental agency
                </a>
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
    <hr />

    <main>

        <?php echo "<label class='error'>" . $_GET['error'] . "</label>"; ?>
    </main>

    <hr />
    <footer>
        <em>
            Birzeit, Palestine
            <a href="mailto:aws.ayyash.1@gmail.com"> E-mail</a> &copy; 2021.</em>
    </footer>
</body>

</html>