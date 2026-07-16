<?php

class Abonnement
{
    private DateTime $DateDebut;
    private DateTime $DateFin;
    private TYPE_ABONNEMENT $type_abonnement;
    private Responsable $responsable;

    public function __construct($DateDebut = 0000-00-00, $DateFin = 0000-00-00)
    {
        $this->DateDebut = $DateDebut;
        $this->DateFin = $DateFin;
    }

    public function getDataDebut(): DateTime
    {
        return $this->DateDebut;
    }

    public function setDataDebut($dateDebut): void
    {
        $this->DateDebut = $dateDebut;
    }


    public function getDataFin(): DateTime
    {
        return $this->DateFin;
    }

    public function setDataFin($dateFin): void
    {
        $this->DateFin = $dateFin;
    }


    public function getId_Abon(): int
    {
        return $this->id_abonnement;
    }

    public function getTypeAbonnement(): TYPE_ABONNEMENT
    {
        return $this->type_abonnement;
    }

    public function getResponsable(): Responsable
    {
        return $this->responsable;
    }
}
