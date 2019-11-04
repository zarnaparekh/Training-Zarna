<?php


namespace SimplifiedMagento\FirstModule\Model;


use SimplifiedMagento\FirstModule\Api\PencilInterface;
use SimplifiedMagento\FirstModule\Api\Color;
use SimplifiedMagento\FirstModule\Api\Size;

class Pencil implements PencilInterface
{
    protected $color;
    protected $size;
    protected $name;
    protected $school;

    public function __construct(Color $color, Size $size, $name=null, $school=null)
    {
        $this->school = $school;
        $this->color = $color;
        $this->size = $size;
        $this->name = $name;
    }

    public function getPencilType()
    {
        echo "Pencil has ".$this->color->getColor()." color and ",$this->size->getSize()." size";
    }
}