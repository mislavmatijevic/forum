<?php

function očistiUnos(&$vrijednost, $indeks) {
    $vrijednost = htmlspecialchars($vrijednost);
}

class Baza
{
    private $veza;   
    public function __construct() {
        $this->veza = new mysqli("localhost", "forum_admin", "forum", "forum_baza");
        if ($this->veza->connect_errno) {
            throw new Exception("Neuspjelo povezivanje");
        }
    }
    public function __destruct()
    {
        $this->veza->close();
    }

    /**
     * @param string $upit "SELECT korime FROM korisnik WHERE korime = ?"
     * @param string $vrsteArgumenata "issd"
     * @param array $argumenti [$id, $korime]
     * @return object|array Sve rezultate koji odgovaraju upitu.
     */
    public function izvršiUpit(string $upit, string $vrsteArgumenata = "", array $argumenti = [], bool $naredba = false)
    {
        $pripremljeniUpit = $this->veza->prepare($upit);

        if ($pripremljeniUpit == false) {
            throw new Exception("Problem s bazom (".__LINE__.")");
        }

        if (!empty($vrsteArgumenata)) {
            if ($pripremljeniUpit->bind_param($vrsteArgumenata, ...$argumenti) == false) {
                throw new Exception("Problem s bazom (".__LINE__.")");
            }
        }

        if ($pripremljeniUpit->execute() == false) {
            throw new Exception("Problem s bazom (".__LINE__.")");
        }


        if ($naredba == false) {
            $podaciIzBaze = $pripremljeniUpit->get_result()->fetch_all(MYSQLI_ASSOC);

            array_walk_recursive($podaciIzBaze, "očistiUnos");

            return $podaciIzBaze;
        } else {
            return $this->veza->insert_id;
        }
    }

    /**
     * @return bool "1 - postoji / 0 - ne postoji"
     */
    public function provjeritiKorisnika(string $korime)
    {
        return $this->izvršiUpit("SELECT EXISTS (SELECT * FROM korisnik WHERE korime = ?) as postoji", "s", [$korime])[0]["postoji"];
    }
}