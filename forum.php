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

// Funkcija za provjeru korisničke uloge
function isAdmin() {
    return isset($_SESSION["role"]) && $_SESSION["role"] === "admin";
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
        <a class="for_a" href="search.php">Pretraživanje</a>
        <form method="post" action="">
            <input class="for_a" type="submit" name="logout" value="Odjava">
        </form>
    </div>
    <br>
    <hr>
    <br>

    <?php
        // Prikazuje sve objave
        $query = mysqli_query($conn, "SELECT title, poster, post_desc FROM posting");

        // Provjerava greške u čitanju koda
        if (!$query) {
            die("Query execution failed: " . mysqli_error($conn));
        }

        // Prikazuje se rezultate
        while ($data = mysqli_fetch_assoc($query)) {
            echo "<br><br><a class='for_a' href='show_content.php?title="
            . htmlspecialchars($data['title']) . "'>"
            . htmlspecialchars($data["title"]) . "</a>" . " <br><br><sub style='color:white;'><b>Kreirao: "
            . htmlspecialchars($data["poster"]) . "</sub></b><br><br>"
            . "<span style='color:white;'>" . htmlspecialchars($data["post_desc"]) . "</span><br><br>";

            // Dodaj opciju za brisanje posta ako je korisnik admin
            if (isAdmin()) {
                echo '<form method="post" action="delete_post.php">';
                echo '<input type="hidden" name="title" value="' . htmlspecialchars($data['title']) . '">';
                echo '<input type="submit" name="delete_post" value="Obriši post" class="for_a" style="color: red;">';
                echo '</form><br><br>';
            }
        }

        // Gasi bazu podataka kada je gotov mysqli_close
        mysqli_close($conn);
    ?>
</body>
</html>