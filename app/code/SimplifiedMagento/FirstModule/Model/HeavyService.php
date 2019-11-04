<?php


namespace SimplifiedMagento\FirstModule\Model;


class HeavyService
{
    public function __construct()
    {
        echo "Heavy Service Instantianed"."</br>";
    }

    public function getheayservicemessge()
    {
        echo "message from heavy service class";
    }
}