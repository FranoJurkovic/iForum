<?php
session_start();

// Povezivanje s bazom podataka
$conn = mysqli_connect("localhost", "root", "", "forum");

// Provjerava jeli korisnik prijavljen ako nije odvodi ga do login.php
if (is_null($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Provjeri da li je ID komentara dostupan u URL-u
if (isset($_GET["id"])) {
    $comment_id = $_GET["id"];

    // Izbriše komentar iz baze podataka
    mysqli_query($conn, "DELETE FROM comments WHERE id='$comment_id';");

    // Nakon brisanja, preusmjeri korisnika natrag na stranicu show_content.php
    header("Location: show_content.php?title=" . $title);
    exit();
} else {
    // Ako ID komentara nije naveden u URL-u, preusmjeri korisnika na neku odgovarajuću stranicu
    header("Location: login.php");
    exit();
}
?>