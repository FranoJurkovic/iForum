<?php
$errors = "";

// Provjera da li se zahtjev preko POST-a, ako da, povezivanje s bazom
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "root", "", "forum");

    // Varijable imaju htmlspecialchars jer ako korisnik upiše bilo koji HTML element, da
    // web stranica tretira te podatke kao običan string
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    // Ako su unosi prazni ili email nije ispravan, ispisuje se greška "Neispravni unosi"
    if (empty($username) || empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors = "Neispravni unosi";
    } else {
        // Ako je sve ispravno, šifra se kriptira pomoću funkcije password_hash
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Dodavanje novog korisnika u bazu podataka s ulogom "user"
        $query = mysqli_query($conn, "INSERT INTO register(username, email, password, role) VALUES ('$username', '$email', '$password', 'user')");

        // Ako je upit uspješan, ispiše se poruka "Korisnički račun kreiran"
        if ($query) {
            echo "Korisnički račun kreiran";
        } else {
            // Ako nije uspješan, ispiše se poruka "Neradi"
            echo "Neradi";
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
    <!-- Povezivanje s CSS-om -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Registracija</h1>

    <!-- Ako postoji greška, ispisuje se u paragrafu -->
    <p style="color:red;"><?php echo $errors; ?></p>

    <!-- Unutar forme ispisivanje se radi s htmlspecialchars zbog sigurnosti,
    ako netko pokuša pisati HTML elemente, da se tretiraju kao običan string -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
        <input type="text" name="username" placeholder="Korisničko ime" class="reg_i"> <br>
        <input type="text" name="email" placeholder="E-mail" class="reg_i"> <br>
        <input type="password" name="password" placeholder="Lozinka" class="reg_i"> <br><br>
        <input type="submit" value="Registriraj se" class="reg_tipka">
    </form>
    <br>

    <!-- Ako korisnik ima račun, može se prijaviti preko poveznice -->
    <a href="login.php" class="reg_a">Prijava</a>
</body>
</html>