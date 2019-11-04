<?php


namespace SimplifiedMagento\FirstModule\Model;

use SimplifiedMagento\FirstModule\Api\Color;
use SimplifiedMagento\FirstModule\Api\Size;

class Book
{
    protected $color;
    protected $size;
    public function __construct(Color $color, Size $size)
    {
        $this->color = $color;
        $this->size = $size;
    }
}