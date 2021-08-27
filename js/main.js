var hamburger = document.querySelector(".hamburger");

let hamburgerOtvoren = false;

hamburger.addEventListener("click", () => {

    hamburgerOtvoren = !hamburgerOtvoren;

    var navigacija = document.querySelector(".nav");

    navigacija.style.height = hamburgerOtvoren ? "300px" : "0px";

    var listaLinkova = document.getElementsByClassName("nav__item");

    for (let index = 0; index < listaLinkova.length; index++) {
        const element = listaLinkova[index];
        if (hamburgerOtvoren) {
            element.style.display = "block";
        } else {
            setTimeout(() => {
                element.style.display = "none";
            }, 100);
        }
    }

});

switch (document.location.pathname) {
    case "/registracija.php": {

        var poljeProblema = [];

        var korime = document.getElementById("korime");
        var mail = document.getElementById("mail");
        var lozinka = document.getElementById("lozinka");
        var lozinkaPonovljena = document.getElementById("lozinkaPonovljena");

        function prikažiIspravnost(nazivElementa) {
            if (poljeProblema[nazivElementa] === false) {
                document.getElementById(nazivElementa).style.border = "5px lightgreen solid";
                document.getElementById(`${nazivElementa}-problem`).innerHTML = "";
            } else {
                document.getElementById(nazivElementa).style.border = "5px red solid";
                document.getElementById(`${nazivElementa}-problem`).innerHTML = poljeProblema[nazivElementa];
            }
        }

        async function korimeProvjera() {
            if (RegExp(/^.{3,45}$/).test(korime.value) === false) {
                poljeProblema["korime"] = "Korisničko ime nije valjano";
            } else {

                try {
                    const odgovorJSON = await fetch(`../api/servisi.php?korime=${korime.value}`);
                    const odgovor = await odgovorJSON.json();

                    if (odgovor.podaci == 1) {
                        poljeProblema["korime"] = odgovor.poruka;
                    } else {
                        poljeProblema["korime"] = false;
                    }
                } catch (error) {
                    poljeProblema["korime"] = false;
                }

            }
            prikažiIspravnost("korime");
        }
        function mailProvjera() {
            if (RegExp(/^\w+@\w+\.\w{2,4}$/).test(mail.value) === false) {
                poljeProblema["mail"] = "Unesite valjan mail!";
            } else {
                poljeProblema["mail"] = false;
            }
            prikažiIspravnost("mail");
        }
        function lozinkaProvjera() {
            if (RegExp(/^(?=.*\d)(?=.*[a-zšđčćžŠĐČĆŽ])[\wšđčćžŠĐČĆŽ]{4,}$/).test(lozinka.value) === false) {
                poljeProblema["lozinka"] = "Unesite valjanu lozinku!";
            } else {
                poljeProblema["lozinka"] = false;
            }
            prikažiIspravnost("lozinka");
            if (lozinkaPonovljena.value.length) lozinkaPonovljenaProvjera();
        }
        function lozinkaPonovljenaProvjera() {
            if (lozinkaPonovljena.value !== lozinka.value || lozinkaPonovljena.value.length === 0) {
                poljeProblema["lozinkaPonovljena"] = "Lozinke se ne poklapaju!";
            } else {
                poljeProblema["lozinkaPonovljena"] = false;
            }
            prikažiIspravnost("lozinkaPonovljena");
        }

        korime.addEventListener("focusout", korimeProvjera)
        mail.addEventListener("focusout", mailProvjera)
        lozinka.addEventListener("focusout", lozinkaProvjera)
        lozinkaPonovljena.addEventListener("focusout", lozinkaPonovljenaProvjera)

        document.querySelector(".forma-podaci").addEventListener("submit", (event) => {

            korimeProvjera();
            mailProvjera();
            lozinkaProvjera();
            lozinkaPonovljenaProvjera();

            for (let index = 0; index < poljeProblema.length; index++) {
                if (poljeProblema[index] !== false) {
                    event.preventDefault();
                }
            }

        });

        break;
    }
    case '/forum.php': {

        const novaObjava = document.querySelector('.objave__nova-objava');
        novaObjava.addEventListener('click', () => {

            location.href = './nova-objava.php';

        });

        const dumgićiDetaljnije = document.getElementsByClassName('forum-card__detaljnije');

        for (let index = 0; index < dumgićiDetaljnije.length; index++) {
            const element = dumgićiDetaljnije[index];
            
            element.addEventListener('click', () => {
                location.href = `./objava.php?id=${element.attributes['idObjava'].value}`;
            });
        }

        break;
    }
    case '/nova-objava.php': {

        document.querySelector('.forma-podaci').addEventListener('submit', async (event) => {
            event.preventDefault();

            const naziv = document.getElementById('naziv');
            const tekst = document.getElementById('tekst');

            if (naziv.value.length < 3 || tekst.value.length < 5) {
                return alert('Prekratki naslov ili tekst!');
            }

            const errorElement = document.querySelector('.error');

            try {
                const novaObjava = { naziv: naziv.value, tekst: tekst.value };

                const parametri = new URLSearchParams;
                parametri.append("nova-objava", JSON.stringify(novaObjava));

                const odgovorJSON = await fetch('../api/servisi.php', { method: 'POST', body: parametri });

                if (odgovorJSON.ok) {
                    location.href = './forum.php';
                    return;
                }

                const odgovor = await odgovorJSON.json();
                errorElement.innerHTML = odgovor.poruka;


            } catch (error) {
                errorElement.innerHTML = error.toString();
            }
        })

        break;
    }
    case '/objava.php': {

        const errorElement = document.querySelector('.error');

        const idObjave = location.search.split('id=')[1];
        const sekcija = document.querySelector('.komentari');
        
        const unesiKomentar = (korime, tekst, separator = false) => {
            tekst = tekst.replaceAll('\n','<br>');
            sekcija.innerHTML +=
                    `
                    <div class="komentar">
                        <div class="komentar__autor">${korime}</div>
                        <div class="komentar__tekst">${tekst}</div>
                    </div>
                    ${separator ? `<span class='separator'></span>` : ''}
                    `;
        }
        const dohvatiKomentare = async() => {
            try {
                const odgovorJSON = await fetch(`../api/servisi.php?komentari=${idObjave}`);
                const odgovor = await odgovorJSON.json();


                odgovor.podaci.forEach((vrijednost, indeks) => {
                    unesiKomentar(vrijednost.korime, vrijednost.tekst, !indeks);
                });


            } catch (error) {
                errorElement.innerHTML = error.toString();
            }
        }
        dohvatiKomentare();

        const tekstKomentara = document.querySelector('.unos-komentara__tekst');
        const dugmeObjavi = document.querySelector('.unos-komentara__objavi');
        dugmeObjavi.addEventListener('click', async () => {

            if (tekstKomentara.value.length < 2) {
                return alert('Komentar prekratak');
            }

            const komentarObjekt = {tekst: tekstKomentara.value, idObjave };

            const parametri = new URLSearchParams;
            parametri.append('novi-komentar', JSON.stringify(komentarObjekt))

            try {
                dugmeObjavi.hidden = "true";
                const odgovorJSON = await fetch('../api/servisi.php', {method: 'POST', body: parametri});
                const odgovor = await odgovorJSON.json();

                if (odgovor.uspjeh) {
                    unesiKomentar(odgovor.podaci.korime, odgovor.podaci.tekst);
                    tekstKomentara.value = '';
                } else {
                    errorElement.innerHTML = odgovor.poruka;
                }
                dugmeObjavi.hidden = "";

            } catch (error) {
                errorElement.innerHTML = error.toString();
            }
        });

        break;
    }
}