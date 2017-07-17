<?php

/*
 *
 *    _______                                _
 *   |__   __|                              | |
 *      | | ___  ___ ___  ___ _ __ __ _  ___| |_
 *      | |/ _ \/ __/ __|/ _ \  __/ _` |/ __| __|
 *      | |  __/\__ \__ \  __/ | | (_| | (__| |_
 *      |_|\___||___/___/\___|_|  \__,_|\___|\__|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Tessetact Team
 * 
 *
*/

namespace pocketmine\block;

class WetSponge extends Solid {

	protected $id = self::WET_SPONGE;

	/**
	 * WetSponge constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 3;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.6;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "Wet Sponge";
	}
}
