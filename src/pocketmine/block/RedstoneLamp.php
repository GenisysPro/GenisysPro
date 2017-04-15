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

class RedstoneLamp extends Solid{
	protected $id = self::REDSTONE_LAMP;
	
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getLightLevel(){
		return 0;
	}

	public function getName() : string{
		return "Redstone Lamp";
	}

	public function turnOn(){
		$this->getLevel()->setBlock($this, new LitRedstoneLamp(), true, true);
		return true;
	}
}
