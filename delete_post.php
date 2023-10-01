<?php
session_start();

// Provjera je li korisnik prijavljen
if (is_null($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Provjera je li korisnik admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: forum.php");
    exit();
}

// Varijable za povezivanje s bazom podataka
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "forum";

// Uspostavljanje veze s bazom podataka
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Provjera je li uspostavljena veza s bazom podataka
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_post"])) {
    // Dohvaćanje naslova posta iz POST podataka
    $title = mysqli_real_escape_string($conn, $_POST["title"]);

    // Izvrši upit za brisanje posta iz baze podataka
    $delete_query = "DELETE FROM posting WHERE title='$title';";
    $result = mysqli_query($conn, $delete_query);

    if ($result) {
        // Ako je brisanje uspješno, preusmjeri korisnika na forum.php
        header("Location: forum.php");
        exit();
    } else {
        // Ako je došlo do greške, ispiši poruku o grešci
        echo "Greška pri brisanju posta: " . mysqli_error($conn);
    }
}

// Zatvori vezu s bazom podataka
mysqli_close($conn);
?>