document.addEventListener("readystatechange", () => {

    if (document.readyState !== "complete") return;

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

            function korimeProvjera() {
                if (RegExp(/^.{3,45}$/).test(korime.value) === false) {
                    poljeProblema["korime"] = "Korisničko ime nije valjano";
                } else {
                    poljeProblema["korime"] = false;
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
    }

});