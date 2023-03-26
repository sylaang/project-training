<?php

namespace App\Service;

class TvaService {

    public function calcul(float $prix){
        return $prix * 0.2;
    }

    public function calcul_ht(float $prix){

        // on considere que le prix d'origine est en TTC
        $tva=$prix*0.2;
        return $prix-$tva;


    }

    
}