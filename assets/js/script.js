document.getElementById('menu-btn').addEventListener('click', function() {
    document.getElementById('menu-nav').classList.toggle('open');
});

var filtres = document.getElementById('filtres');
if (filtres) {
    filtres.addEventListener('input', function () {
        var prixMin = document.getElementById('prix-min').value;
        var prixMax = document.getElementById('prix-max').value;
        var theme = document.getElementById('filtre-theme').value;
        var regime = document.getElementById('filtre-regime').value;
        var personnes = document.getElementById('filtre-personnes').value;
        var cartes = document.querySelectorAll('.carte-menu');
        var visibles = 0;

        cartes.forEach(function (carte) {
            var prix = Number(carte.dataset.prix);
            var ok = true;
            if (prixMin !== '' && prix < Number(prixMin)) ok = false;
            if (prixMax !== '' && prix > Number(prixMax)) ok = false;
            if (theme !== '' && carte.dataset.theme !== theme) ok = false;
            if (regime !== '' && carte.dataset.regime !== regime) ok = false;
            if (personnes !== '' && Number(carte.dataset.personnes) > Number(personnes)) ok = false;
            carte.hidden = !ok;
            if (ok) visibles++;
        });

        document.getElementById('aucun-menu').hidden = visibles !== 0;
    });
}



