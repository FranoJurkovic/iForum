<?php
    $errors = "";

    //Provjera dali se zahtjev preko POST-a ako je povezuje se s bazom
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = mysqli_connect("localhost", "root", "", "forum");

        //Varijable imaju htmlspecialchars jer ako korinsik upiše bilo koji html element da
        //web stranica tretira te podatke kao običan string
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);

        //Ako korisnik nije upisao ništa samo pritisnuo pošalji ispisat će mu neispravan unos
        if (empty($username) || empty($password)) {
            $errors = "Neispravan unos!";
        }

        //Ako je ispravan unos podaci se uzimaju u asocijativni niz
        else {
            $query = mysqli_query($conn, "SELECT username, password FROM register WHERE username='$username';");
            $data = mysqli_fetch_assoc($query);

            //Ako username nema u bazi ispisuje da nema korisnika
            if (is_null($data["username"])) {
                $errors = "Korisničko ime nepostoji!";
            }

            //Provjera jeli ispravna šifra to jest dali se podudara sa šifrom iz baze podataka
            else {

                //Ako je šifra ispravna korisnik ide do forum.php stranice
                if (password_verify($password, $data["password"])) {
                    session_start();
                    $_SESSION["username"]=$username;
                    header("Location: forum.php");
                }

                //Lozinka nije dobra pa se korisnik nije mogao prijaviti
                else {
                    $errors = "Lozinka nije ispravna";
                }
            }
        }
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
</head>
<body>
    <h1>Prijava</h1>

    <!--Ukoliko postoji greška ispisat će se u paragrafu-->
    <p style="color:red"><?php echo $errors; ?></p>

    <!--Unutar forme ispisivanje se radi s htmlspecialchars zbog sigurnosti 
    ako netko pokuša pisati html elemente da se tretiraju kao običan string-->
    <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?> method="POST">
        <input type="text" name="username" placeholder="Korisničko ime" class="prijava_i"> <br>
        <input type="password" name="password" placeholder="Lozinka" class="prijava_i"> <br><br>
        <input type="submit" value="Prijava" class="pri_tipka">
    </form>
    <br>

    <!--Ukoliko korisnik nema račun da se može registrirati preko poveznice-->
    <a href="register.php" class="pri_a">Registracija</a>
</body>
</html>