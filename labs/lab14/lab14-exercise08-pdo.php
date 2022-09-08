<?php require_once('config.php'); ?>
<!DOCTYPE html>
<html>

<body>
    <nav>
        <ul>
            <li>
                <p><a href="./index.html">Lab14 home page</a></p>
            </li>
        </ul>
    </nav>
    <h1>Database Tester (PDO)</h1>

    <?php
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from Artists order by LastName";
        $result = $pdo->query($sql);
        while ($row = $result->fetch()) {
            echo $row['ArtistID'] . " - " . $row['LastName'] . "<br/>";
        }
        $pdo = null;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    ?>
</body>

</html>