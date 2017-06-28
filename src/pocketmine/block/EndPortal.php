<?php

namespace pocketmine\block;

use pocketmine\item\Item;

class EndPortal extends Transparent{

    protected $id = Block::END_PORTAL;

    public function __construct(){

    }

    public function getName(){
        return "End Portal";
    }

    public function getLightLevel(){
        return 15;
    }

    public function getHardness(){
        return -1;
    }

    public function getResistance(){
        return 18000000;
    }

    public function isBreakable(Item $item){
        return false;
    }
}