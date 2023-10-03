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

// Inicijalizacija varijable za rezultate pretrage
$searchResults = [];

// Provjerava jeli su uvjeti kod pretrage točni
if (isset($_GET['searchTitle'])) {
    $searchTitle = $_GET['searchTitle'];
    $searchTitle = mysqli_real_escape_string($conn, $searchTitle);

    // Vraća rezultate s podudaranim naslovom
    $query = mysqli_query($conn, "SELECT title, poster FROM posting WHERE title LIKE '%$searchTitle%'");

    // Provjerava greške u čitanju koda
    if (!$query) {
        die("Query execution failed: " . mysqli_error($conn));
    }

    // Pohranjuje rezultate pretrage u polje $searchResults
    while ($data = mysqli_fetch_assoc($query)) {
        $searchResults[] = $data;
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iForum - Pretraga</title>
    <!-- Povezivanje s CSS-om -->
    <link rel="stylesheet" href="style.css">
    <!--Angular-->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
</head>
<body>
    <div class="header">
        <a class="for_a" href="forum.php">Početna</a>
        <!-- Preko sesije dohvaća korisnika i ispisuje ga -->
        <p>Pozdrav <b><?php echo $_SESSION["username"] ?></b> !</p>
    </div>
    <br>
    <hr>
    <br>

    <h1 style='color: white;'>Pretraživanje naslova</h1>

    <form method="get" action="">
        <div ng-app="myApp" ng-controller="myCtrl">
            <label for="searchTitle" style='color: white;'>Unesite naslov za pretragu:</label><br><br>
            <input type="text" id="searchTitle" name="searchTitle" class="for_i" ng-model="unos_teksta" ng-keyup="prikazi_poruku()" autocomplete="off">
            <button type="submit" class="for_b">Pretraži</button>
            <p style="color:white;" ng-show="prikazi_input_unos">Unijeli ste: {{unos_teksta}}</p>
        </div>
    </form>
    <br>
    <?php
        // Prikaz rezultata pretrage
        if (!empty($searchResults)) {
            echo '<h2 style="color: white;">Rezultati pretrage</h2>';
            foreach ($searchResults as $result) {
                echo "<a class='for_a' href='show_content.php?title="
                . $result['title'] . "'>" . $result["title"]
                . "</a><br>" . "<br><sub style='color: white;'><b>Kreirao "
                . $result["poster"] . "</sub></b><hr><br>";
            }
        } elseif (isset($_GET['searchTitle'])) {
            // Ako nema rezultata
            echo "<p class='for_p' style='color: white;'>Nema rezultata za pretragu po naslovu: <b>$searchTitle</b></p>";
        }
    ?>
    <script>
        var app = angular.module('myApp', []);

        app.controller('myCtrl', function($scope) {
            $scope.prikazi_input_unos = false;

            $scope.prikazi_poruku = function() {
                if ($scope.unos_teksta && $scope.unos_teksta.length > 0) {
                    $scope.prikazi_input_unos = true;
                } else {
                    $scope.prikazi_input_unos = false;
                }
            };
        });
</script>
</body>
</html>