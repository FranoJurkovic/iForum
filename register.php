<?php
    $errors = "";

    //Provjera dali se zahtjev preko POST-a ako je povezuje se s bazom
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = mysqli_connect("localhost", "root", "", "forum");

        //Varijable imaju htmlspecialchars jer ako korinsik upiše bilo koji html element da
        //web stranica tretira te podatke kao običan string

        $username = htmlspecialchars($_POST["username"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);

        //Ako su unosi prazni ili email nije ispravan onda se ispisuje greška Neispravni unosi
        if (empty($username) || empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors = "Neispravni unosi";
        }
        else{

            //Ako je sve ispravno, šifra preko funkcije password_hash dobiva novu šifru koja će skrivati pravu
            $password = password_hash($password, PASSWORD_DEFAULT);
            
            //Preko mysqli_query odabiremo varijable username i email 
            //i spremamo u asocijativni niz preko funkcije mysqli_fetch_assoc
            $query = mysqli_query($conn, "SELECT username, email FROM register WHERE username='$username' OR email='$email'");
            $data = mysqli_fetch_assoc($query);

            // Provjera postoji li e-mail u bazi
            if ($data) {
                if ($data["email"] == $email) {
                    $errors = "E-mail već postoji";
                }

                //Ako je e-mail isti provjerava dali su drugi uvjeti isti ako jesu postoji korisniku vraća grešku
                else {
                    $errors = "Lozinka nije ispravna";
                }
            }

            //Ako u bazi ne postoji račun onda se podaci ubacuju u bazu podataka
            else {
                $query = mysqli_query($conn, "INSERT INTO register(username, email, password) values('$username','$email','$password')");
                
                //Ako je sve u redu vraća se poruka "Korisniči račun kreiran"
                if ($query) {
                    echo "Korisnički račun kreiran";
                }
                
                //Ako nije vraća se poruka "Neradi"
                else {
                    echo "Neradi";
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
    <h1>Registracija</h1>

    <!--Ukoliko postoji greška ispisat će se u paragrafu-->
    <p style="color:red;"><?php echo $errors; ?></p>

    <!--Unutar forme ispisivanje se radi s htmlspecialchars zbog sigurnosti 
    ako netko pokuša pisati html elemente da se tretiraju kao običan string-->
    <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?> method="POST">
        <input type="text" name="username" placeholder="Korisničko ime" class="reg_i"> <br>
        <input type="text" name="email" placeholder="E-mail" class="reg_i"> <br>
        <input type="password" name="password" placeholder="Lozinka" class="reg_i"> <br><br>
        <input type="submit" value="Registriraj se" class="reg_tipka">
    </form>
    <br>

    <!--Ukoliko korisnik ima račun da se može prijaviti preko poveznice-->
    <a href="login.php" class="reg_a">Prijava</a>
</body>
</html>