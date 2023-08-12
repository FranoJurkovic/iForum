<?php
    // Provjera da li korisničko ime postoji (provjera prijave), inače preusmjeri na login.php
    session_start();
    if (is_null($_SESSION["username"])) {
        header("Location: login.php");
    }

    // Povezivanje s bazom podataka
    $conn = mysqli_connect("localhost", "root", "", "forum");

    // Dohvaćanje naslova iz URL-a
    $title = $_GET["title"];

    // Preusmjeravanje na forum.php ako naslov nije naveden u URL-u
    if (is_null($title)) {
        header("Location: forum.php");
    }

    // Dohvaćanje podataka o objavi
    $query = mysqli_query($conn, "SELECT poster, title, post_desc FROM posting WHERE title='$title';");
    $data = mysqli_fetch_assoc($query);

    // Preusmjeravanje na forum.php ako naslov ne postoji u bazi
    if (is_null($data["title"])) {
        header("Location: forum.php");
    }

    // Provjera je li korisnik poslao komentar
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Dohvati korisničko ime iz sesije
        $poster = $_SESSION["username"];

        // Dohvati komentar iz POST podataka
        $comment = $_POST["comment"];

        // Dodaj komentar u bazu podataka
        $comment = mysqli_real_escape_string($conn, $comment); // Zaštita od SQL injection
        $insert_query = "INSERT INTO comments (title, poster, comment) VALUES ('$title', '$poster', '$comment')";
        mysqli_query($conn, $insert_query);
    }

    // Dohvati sve komentare za ovu objavu
    $comments_query = mysqli_query($conn, "SELECT poster, comment, created_at FROM comments WHERE title='$title' ORDER BY created_at DESC;");
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data["title"]; ?></title>
    <!--Povezivanje s css-om-->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="forum.php" class="show_a">Natrag</a>

    <h1><?php echo $data["title"]; ?></h1>
    <sup class="show_sup">Kreirao: <?php echo $data["poster"]; ?></sup><br><hr>
    <p class="show_p"><?php echo $data["post_desc"]; ?></p><br><br>
    <!-- Dodavanje komentara -->
    <form method="post" action="">
        <textarea name="comment" placeholder="Unesite komentar" rows="4" cols="50" class="show_area"></textarea><br>
        <input type="submit" value="Dodaj komentar" class="show_a">
    </form><br>
    <!-- Ispis postojećih komentara -->
    <?php
        while ($comment_data = mysqli_fetch_assoc($comments_query)) {
            echo "<p class='comment'><b>" . $comment_data["poster"] . ":</b> " . $comment_data["comment"] . "</p>";
            echo "<p class='comment-time'>" . formatCommentTime($comment_data["created_at"]) . "</p><hr>";
        }
    ?>
</body>
</html>

<?php
    // Funkcija za formatiranje vremena komentara
    function formatCommentTime($timestamp) {
        // Pretvori vremenski znak u ljudski čitljiv format
        return "Kreirano: " . date("d.m.Y H:i:s", strtotime($timestamp));
    }
?>