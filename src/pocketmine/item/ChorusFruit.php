<?php

/*
 *
 *  _______                                _   
 * |__   __|                              | |  
 *    | | ___  ___ ___  ___ _ __ __ _  ___| |_ 
 *    | |/ _ \/ __/ __|/ _ \ '__/ _` |/ __| __|
 *    | |  __/\__ \__ \  __/ | | (_| | (__| |_ 
 *    |_|\___||___/___/\___|_|  \__,_|\___|\__|
 *                                             
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

namespace pocketmine\item;


class ChorusFruit extends Food {
	/**
	 * ChorusFruit constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::CHORUS_FRUIT, 0, $count, "Chorus Fruit");
	}

	/**
	 * @return int
	 */
	public function getFoodRestore() : int{
		return 4;
	}

	/**
	 * @return float
	 */
	public function getSaturationRestore() : float{
		return 2.4;
	}

}