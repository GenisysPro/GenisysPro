<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\event\player;

use pocketmine\entity\Human;
use pocketmine\event\Cancellable;

class PlayerExperienceChangeEvent extends PlayerEvent implements Cancellable {
	public static $handlerList = null;

	public $progress;
	public $expLevel;

	/**
	 * PlayerExperienceChangeEvent constructor.
	 *
	 * @param Human $player
	 * @param int   $expLevel
	 * @param float $progress
	 */
	public function __construct(Human $player, int $expLevel, float $progress){
		$this->progress = $progress;
		$this->expLevel = $expLevel;
		$this->player = $player;
	}

	/**
	 * @return int
	 */
	public function getExpLevel(){
		return $this->expLevel;
	}

	/**
	 * @param $level
	 */
	public function setExpLevel($level){
		$this->expLevel = $level;
	}

	/**
	 * @return float
	 */
	public function getProgress() : float{
		return $this->progress;
	}

	/**
	 * @param float $progress
	 */
	public function setProgress(float $progress){
		$this->progress = $progress;
	}

	/**
	 * @return int
	 */
	public function getExp(){
		return Human::getLevelXpRequirement($this->expLevel) + $this->progress;
	}

	/**
	 * @param $exp
	 */
	public function setExp($exp){
		$this->progress = $exp / Human::getLevelXpRequirement($this->expLevel);
	}
}
