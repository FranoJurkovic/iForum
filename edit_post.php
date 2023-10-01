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

// Dohvaćanje naslova iz URL-a
$title = $_GET["title"];

// Preusmjeravanje na forum.php ako naslov nije naveden u URL-u
if (is_null($title)) {
    header("Location: forum.php");
    exit();
}

// Dohvaćanje podataka o objavi
$query = mysqli_query($conn, "SELECT poster, title, post_desc FROM posting WHERE title='" . mysqli_real_escape_string($conn, $title) . "';");
$data = mysqli_fetch_assoc($query);

// Preusmjeravanje na forum.php ako naslov ne postoji u bazi
if (is_null($data["title"])) {
    header("Location: forum.php");
    exit();
}

// Provjera je li trenutni korisnik autor objave ili admin
$isAuthor = ($_SESSION["username"] == $data["poster"]) || (isset($_SESSION["role"]) && $_SESSION["role"] === "admin");

// Provjera je li korisnik poslao zahtjev za uređivanjem objave
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_post_submit"])) {
    // Dohvati novi naslov i sadržaj objave iz POST podataka
    $newTitle = mysqli_real_escape_string($conn, $_POST["new_title"]);
    $newDesc = mysqli_real_escape_string($conn, $_POST["new_desc"]);

    // Ažuriraj postojeći post u bazi podataka
    $update_query = "UPDATE posting SET title='$newTitle', post_desc='$newDesc' WHERE title='" . mysqli_real_escape_string($conn, $title) . "';";
    mysqli_query($conn, $update_query);

    // Preusmjeri korisnika na stranicu za prikaz posta
    header("Location: show_content.php?title=$newTitle");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uređivanje posta</title>
    <!-- Povezivanje s CSS-om -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="show_content.php?title=<?php echo htmlspecialchars($data["title"]); ?>" class="show_a">Natrag</a><br><br>

    <?php
    if ($isAuthor) {
        // Prikaži formu za uređivanje ako je korisnik autor posta
        echo '<form method="post" action="">';
        echo '<input type="text" ng-model="name" class="cre_i" id="new_title" name="new_title" value="' . htmlspecialchars($data['title']) . '" required><br>';
        echo '<textarea id="new_desc" class="cre_a" name="new_desc" rows="8" cols="80" required>' . htmlspecialchars($data['post_desc']) . '</textarea><br>';
        echo '<input type="submit" name="edit_post_submit" value="Spremi promjene" class="show_a">';
        echo '</form>';
    } else {
        echo '<p>Nemate ovlasti za uređivanje ovog posta.</p>';
    }
    ?>

</body>
</html>