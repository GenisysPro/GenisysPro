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
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Tessetact Team
 * @link http://www.github.com/TesseractTeam/Tesseract
 * 
 *
 */
 
namespace pocketmine\tile;

use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\level\Level;

class BeaconDelayedCheckTask extends Task {
	
	private $pos;
	private $level;
	
	public function __construct(Vector3 $pos, Level $level) {
		$this->pos = $pos;
		$this->level = $level;
	}
	
	public function onRun($currentTick) {
		$level = $this->level;
		if (!Server::getInstance()->isLevelLoaded($level->getName())) return;
		$tile = $level->getTile($this->pos);
		if ($tile instanceof Beacon) {
			$tile->scheduleUpdate();
		}
	}
}