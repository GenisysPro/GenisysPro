<?php

/*
 *
 *  _____            _               _____           
 * / ____|          (_)             |  __ \          
 *| |  __  ___ _ __  _ ___ _   _ ___| |__) | __ ___  
 *| | |_ |/ _ \ '_ \| / __| | | / __|  ___/ '__/ _ \ 
 *| |__| |  __/ | | | \__ \ |_| \__ \ |   | | | (_) |
 * \_____|\___|_| |_|_|___/\__, |___/_|   |_|  \___/ 
 *                         __/ |                    
 *                        |___/                     
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author GenisysPro
 * @link https://github.com/GenisysPro/GenisysPro
 *
 *
*/

namespace pocketmine\block;

use pocketmine\Player;
use pocketmine\item\Item;

class EndRod extends Flowable{

	protected $id = self::END_ROD;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getLightLevel(){
		return 14;
	}

	public function getName() : string {
		return "End Rod";
	}

	public function getResistance(){
        return 0;
    }
	
	public function getHardness(){
        return 0;
    }

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
			$faces = [
				0 => 0,
				1 => 1,
				2 => 3,
				3 => 2,
				4 => 5,
				5 => 4,
			];
			$this->meta = ($target->getId() === self::END_ROD && $faces[$face] == $target->getDamage()) ? Vector3::getOppositeSide($faces[$face]) : $faces[$face];
			$this->getLevel()->setBlock($block, $this, true, true);
			return true;
		}

	public function getDrops(Item $item) : array {
		return [
			[$this->id, 0, 1],
		];
	}

}
