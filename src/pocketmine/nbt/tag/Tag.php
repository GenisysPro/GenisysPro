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

/**
 * All the NBT Tags
 */

namespace pocketmine\nbt\tag;

use pocketmine\nbt\NBT;

abstract class Tag extends \stdClass {

	protected $value;

	/**
	 * @return mixed
	 */
	public function &getValue(){
		return $this->value;
	}

	/**
	 * @return mixed
	 */
	public abstract function getType();

	/**
	 * @param $value
	 */
	public function setValue($value){
		$this->value = $value;
	}

	/**
	 * @param NBT  $nbt
	 * @param bool $network
	 *
	 * @return mixed
	 */
	abstract public function write(NBT $nbt, bool $network = false);

	/**
	 * @param NBT  $nbt
	 * @param bool $network
	 *
	 * @return mixed
	 */
	abstract public function read(NBT $nbt, bool $network = false);

	/**
	 * @return string
	 */
	public function __toString(){
		return (string) $this->value;
	}
}
