document.addEventListener("DOMContentLoaded", function () {
    // Dohvati elemente iz DOM-a
    var unos = document.getElementById("unos");
    var ispis = document.getElementById("ispis");

    // Dodaj event listener za praćenje unosa u input polje
    unos.addEventListener("input", function () {
        // Dohvati uneseni tekst
        var unos_tekst = unos.value;

        // Ispiši uneseni tekst u odlomak
        ispis.textContent = "Unijeli ste: " + unos_tekst;
    });
});