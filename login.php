<?php
$errors = "";

// Provjera da li je zahtjev poslan preko POST metode
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "root", "", "forum");

    // Varijable imaju htmlspecialchars kako bi se spriječio XSS napad
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    if (empty($username) || empty($password)) {
        $errors = "Neispravan unos!";
    } else {
        $query = mysqli_query($conn, "SELECT username, password, role FROM register WHERE username='$username';");
        $data = mysqli_fetch_assoc($query);

        if (is_null($data)) {
            $errors = "Korisničko ime ne postoji!";
        } else {
            if (password_verify($password, $data["password"])) {
                // Postavljanje korisničkog imena u sesiju
                session_start();
                $_SESSION["username"] = $username;
                
                // Postavljanje uloge korisnika u sesiju
                $_SESSION["role"] = $data["role"];

                header("Location: forum.php");
            } else {
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Prijava</h1>

    <p style="color: red;"><?php echo $errors; ?></p>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="text" name="username" placeholder="Unesite korisničko ime" class="prijava_i" autocomplete="off"><br>
        <input type="password" name="password" placeholder="Unesite lozinku" class="prijava_i" autocomplete="off"><br><br>
        <input type="submit" value="Prijava" class="pri_tipka">
    </form>
    <br>

    <a href="register.php" class="pri_a">Registracija</a>
</body>
</html>