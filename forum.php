<?php
    session_start();

    // Varijable za povezivanje s bazom podataka
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "forum";

    // Uspostavljanje veze s bazom podataka
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    // Provjerava dali baza podataka ima grešaka
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Provjerava jeli korisnik prijavljen ako nije odvodi ga do login.php
    if (is_null($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }

    // Ako korisnik klikne na odjavu kod gasi sesiju
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iForum</title>
    <!--Povezivanje s css-om-->
    <link rel="stylesheet" href="style.css">

    <!--Script src za meni oznaku za sužene ekrane i ikonu za korisnika-->
    <script src="https://kit.fontawesome.com/e7fff21bea.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="header">
        <a class="for_a" href="forum.php">Početna</a>
        <!--Preko sesije dohvaća korisnika i ispisuje ga-->
        <p>Pozdrav <b><?php echo $_SESSION["username"] ?></b> !</p>
        <a class="for_a" href="create_post.php">Kreiraj novu objavu</a>
        <form method="get" action="">
            <label for="searchTitle">Pretraži naslove:</label>
            <input type="text" id="searchTitle" name="searchTitle" class="for_i" >
            <button type="submit" class="for_b"><i class="fa fa-search"></i></button>
        </form>
        <form method="post" action="">
            <input class="for_a" type="submit" name="logout" value="Odjava" class="header-link header-logout-button">
        </form>
    </div>
    <br>
    <hr>

    <?php
        // Provjerava dali su uvjeti kod pretrage točni
        if (isset($_GET['searchTitle'])) {
            $searchTitle = $_GET['searchTitle'];
            $searchTitle = mysqli_real_escape_string($conn, $searchTitle);

            // Vraća rezutlate s podudaranim nazivom naslova
            $query = mysqli_query($conn, "SELECT title, poster FROM posting WHERE title LIKE '%$searchTitle%'");

            // Provjerava greške u čitanju koda
            if (!$query) {
                die("Query execution failed: " . mysqli_error($conn));
            }

            // Prikazuje filtrirane objave
            while ($data = mysqli_fetch_assoc($query)) {
                echo "<a class='for_a' href='show_content.php?title=".$data['title']."'>".$data["title"]."</a>"." <br><br><sub><b>Kreirao ".$data["poster"]."</sub><b><hr><br>";
            }

            // Ako rezultat za pretragu nema izbaci poruku
            if (mysqli_num_rows($query) === 0) {
                echo "<p class='for_p'>Nema rezultata za pretragu po naslovu: <b>$searchTitle</b></p><br>";
            }
        } else {
            // Prikazuje sve objave
            $query = mysqli_query($conn, "SELECT title, poster FROM posting");

            // Provjerava greške u čitanju koda
            if (!$query) {
                die("Query execution failed: " . mysqli_error($conn));
            }

            // Prikazuje se rezultate
            while ($data = mysqli_fetch_assoc($query)) {
                echo "<a class='for_a' href='show_content.php?title=".$data['title']."'>".$data["title"]."</a>"." <br><br><sub><b>Kreirao ".$data["poster"]."</sub><b><hr><br>";
            }
        }

        // Gasi bazu podataka kada je gotov mysqli_close
        mysqli_close($conn);
    ?>
</body>
</html>