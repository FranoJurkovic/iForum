<?php
    session_start();
    $errors="";

    //Provjera dali ima korisnika ako nema odvede ga do login.php prijave
    if(is_null($_SESSION["username"])){
        header("Location: login.php");
    }

    //Provjera dali se zahtjev preko POST-a ako je povezuje se s bazom
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = mysqli_connect("localhost", "root", "", "forum");

        //Unutar baze poster nema vrijednosti nego preko $_SESSION 
        //dodajemo mu vrijednost korisničkog imena
        $poster=$_SESSION["username"];

        //Varijable imaju htmlspecialchars jer ako korinsik upiše bilo koji html element da
        //web stranica tretira te podatke kao običan string
        $title=htmlspecialchars($_POST["title"]);
        $post_desc=htmlspecialchars($_POST["desc"]);

        //Ukoliko korisnik ništa nije napisao za naslov i opis 
        //pojavit će mu se greška neispravni unosi
        if(empty($title) || empty($post_desc)){
            $errors="Neispravni unosi!";
        }

        //Ukoliko je nešto napisao podaci se spremaju u asocijativni niz
        else{
            $query=mysqli_query($conn, "SELECT title FROM posting WHERE title='$title'");
            $data=mysqli_fetch_assoc($query);

            //Ako naslov postoji nije moguće kreirati drugu objavu s istim naslovom
            if(!is_null($data["title"])){
                $errors="Naslov već postoji!";
            }
            else{

                //Podaci iz varijabla se spremaju u bazu podataka
                $query = mysqli_query($conn, "INSERT INTO posting(poster, title, post_desc) values('$poster','$title','$post_desc')");

                //Ako su podaci spremjeni u bazu podataka vrati nas do forum stranice
                if($query){
                    header("Location: forum.php");
                }

                //Ako se podaci ne mogu spremiti vraća grešku
                else{
                    $errors="Greška pri kreiranju objave";
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

    <!--Mogućnost za vraćanje nazad preko poveznice-->
    <a href="forum.php" class="cre_a">Natrag</a>
    <h1>Kreiraj novu objavu</h1>

    <!--Ukoliko postoji greška ispisat će se u paragrafu-->
    <p style="color:red;"><?php echo $errors; ?></p>

    <!--Unutar forme ispisivanje se radi s htmlspecialchars zbog sigurnosti 
    ako netko pokuša pisati html elemente da se tretiraju kao običan string-->
    <form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?> method="POST">
        <input type="text" name="title" placeholder="Naslov" class="cre_i" id="unos"> <br>
        <p style="color:white;" id="ispis"></p>
        <textarea name="desc" rows="4" cols="50" placeholder="Sadržaj teme.." class="cre_area"></textarea><br><br>
        <input type="submit" class="cre_tipka" value="Objavi">
    </form>
    <script src="main.js"></script>
</body>
</html>