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

class WetSponge extends Solid{

	protected $id = self::WET_SPONGE;

	public function __construct(){
	}
	public function getResistance(){
		return 3;
	}
	public function getHardness(){
		return 0.6;
	}
	public function getName(){
		return "Wet Sponge";
	}
}
