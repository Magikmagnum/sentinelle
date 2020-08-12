<?php

namespace App\Entity;


class Entity
{
    public function getDatime($var = 'now')
    {
        return new \DateTime($var, new \DateTimeZone('Africa/Libreville'));
    }
}
