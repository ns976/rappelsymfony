<?php

namespace App\Taxes;



use phpDocumentor\Reflection\Types\Boolean;

class Detector
{
    protected $seuil;

    public
    function __construct (int $seuil)
    {
        $this->seuil = $seuil;
    }


    /**
     * Detects if the given amount is greater than 100.
     *
     * @param int $amount The amount to be checked.
     *
     * @return bool True if the amount is greater than 100, false otherwise.
     */
    public function detect(int $amount):bool
    {

        return $amount > $this->seuil;

    }
}
