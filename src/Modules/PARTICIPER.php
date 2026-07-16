<?php

class PARTICIPER
{
    public $jour_activite;
    public $heure_activite;

    public function  get_obj_activite()
    {
        $activite = new ACTIVITE();
        return $activite;
    }


    public function get_obj_adherent()
    {
        $adherent = new Adherent();
        return $adherent;
    }
}
