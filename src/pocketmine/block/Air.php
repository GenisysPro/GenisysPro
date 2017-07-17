<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\block;

use pocketmine\item\Item;


/**
 * Air block
 */
class Air extends Transparent {

	protected $id = self::AIR;
	protected $meta = 0;

	/**
	 * Air constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Air";
	}

	/**
	 * @return bool
	 */
	public function canPassThrough(){
		return true;
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function isBreakable(Item $item){
		return false;
	}

	/**
	 * @return bool
	 */
	public function canBeFlowedInto(){
		return true;
	}

	/**
	 * @return bool
	 */
	public function canBeReplaced(){
		return true;
	}

	/**
	 * @return bool
	 */
	public function canBePlaced(){
		return false;
	}

	/**
	 * @return bool
	 */
	public function isSolid(){
		return false;
	}

	/**
	 * @return null
	 */
	public function getBoundingBox(){
		return null;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 0;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 0;
	}

}