<?php
session_start();

// Povezivanje s bazom podataka
$conn = mysqli_connect("localhost", "root", "", "forum");

// Provjera je li korisničko ime postoji (provjera prijave), inače preusmjeri na login.php
if (is_null($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Dohvati naslov iz URL-a
$title = $_GET["title"];

// Preusmjeravanje na forum.php ako naslov nije naveden u URL-u
if (is_null($title)) {
    header("Location: forum.php");
    exit();
}

// Dohvati podatke o objavi
$query = mysqli_query($conn, "SELECT poster, title, post_desc FROM posting WHERE title='$title';");
$data = mysqli_fetch_assoc($query);

// Preusmjeravanje na forum.php ako naslov ne postoji u bazi
if (is_null($data["title"])) {
    header("Location: forum.php");
    exit();
}

// Provjeri je li trenutni korisnik autor objave
$isAuthor = ($_SESSION["username"] == $data["poster"]);

// Provjeri je li trenutni korisnik admin
$isAdmin = ($_SESSION["role"] == "admin");

// Provjeri je li korisnik poslao zahtjev za uređivanjem objave
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_post"])) {
    // Preusmjeri korisnika na stranicu za uređivanje objave
    header("Location: edit_post.php?title=" . $data["title"]);
    exit();
}

// Provjeri je li korisnik poslao zahtjev za brisanjem objave
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_post"])) {
    // Samo autor ili admin mogu izbrisati objavu
    if ($isAuthor || $isAdmin) {
        mysqli_query($conn, "DELETE FROM posting WHERE title='$title';");
        // Nakon brisanja, preusmjeri korisnika na forum.php
        header("Location: forum.php");
        exit();
    }
}

// Provjeri je li korisnik poslao komentar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comment"])) {
    // Dohvati korisničko ime iz sesije
    $poster = $_SESSION["username"];

    // Dohvati komentar iz POST podataka
    $comment = $_POST["comment"];

    // Provjeri je li komentar prazan
    if (!empty($comment)) {
        // Dodaje komentar u bazu podataka
        $comment = mysqli_real_escape_string($conn, $comment); // Zaštita od SQL injection
        $insert_query = "INSERT INTO comments (title, poster, comment) VALUES ('$title', '$poster', '$comment')";
        mysqli_query($conn, $insert_query);
    } else {
        // Ako je komentar prazan, ispiši poruku
        $comment_error = "Unos nije ispravan. Molimo unesite komentar.";
    }
}

// Dohvati sve komentare za ovu objavu
$comments_query = mysqli_query($conn, "SELECT id, poster, comment, created_at FROM comments WHERE title='$title' ORDER BY created_at DESC;");
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data["title"]; ?></title>
    <!-- Povezivanje s CSS-om -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="forum.php" class="show_a">Natrag</a>

    <h1 style="color: white;"><?php echo $data["title"]; ?></h1>
    <sup class="show_sup">Kreirao: <?php echo $data["poster"]; ?></sup><br><hr>
    <p class="show_p"><?php echo $data["post_desc"]; ?></p>

    <!-- Gumb za uređivanje objave (samo autor ili admin) -->
    <?php
    if ($isAuthor || $isAdmin) {
        echo '<form method="post" action="">';
        echo '<input type="submit" name="edit_post" value="Uredi objavu" class="show_a">';
        echo '</form>';
    }
    ?>

    <!-- Gumb za brisanje objave (samo autor ili admin) -->
    <?php
    if ($isAuthor || $isAdmin) {
        echo '<form method="post" action="">';
        echo '<input type="submit" name="delete_post" value="Obriši objavu" class="show_a" style="color: red;">';
        echo '</form>';
    }
    ?><br><br>
    <!-- Dodavanje komentara -->
    <form method="post" action="">
        <textarea name="comment" placeholder="Unesite komentar" rows="4" cols="50" class="show_area"></textarea><br>
        <?php
        if (isset($comment_error)) {
            echo '<p class="error-message">' . $comment_error . '</p>';
        }
        ?>
        <input type="submit" value="Dodaj komentar" class="show_a">
    </form><br>

    <!-- Ispis postojećih komentara -->
    <?php
    while ($comment_data = mysqli_fetch_assoc($comments_query)) {
        echo "<div class='comment' style='color: white;'>";
        echo "<b>" . $comment_data["poster"] . ":</b> " . $comment_data["comment"];

        // Gumb za brisanje komentara (samo autor komentara ili admin)
        if ($_SESSION["username"] == $comment_data["poster"] || $isAdmin) {
            echo '<br><br><a href="delete_comment.php?id=' . $comment_data["id"] . '" class="show_a" style="color: red;"><i style="font-size:16px" </i> Obriši komentar</a>';
        }

        echo "</div><br><br>";
    }
    ?>
</body>
</html>