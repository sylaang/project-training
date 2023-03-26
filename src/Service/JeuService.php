<?php

namespace App\Service;

class JeuService {

    public function deviner(int $alea, int $nombre ){
        if ($alea == $nombre) {
            $data['reponse']="Gagné";
            return $data['reponse'];
        }else{

            $data['reponse']="Perdu";
            return $data['reponse'];
        } 
    }

    
}