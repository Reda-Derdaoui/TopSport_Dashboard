<?php

class ASSURANCE
{
    private DateTime $date_debut;
    private int $prix;


    public function set_DateDebut(DateTime $dateDebut): void
    {
        $this->date_debut = $dateDebut;
    }
    public function get_DateDebut(): DateTime
    {
        return $this->date_debut;
    }

    public function setPrix(string $prix): void
    {
        $this->prix = $prix;
    }
    public function getPrix(): int
    {
        return $this->prix;
    }
}
