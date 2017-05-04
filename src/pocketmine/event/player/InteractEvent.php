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

namespace pocketmine\event\player;

use pocketmine\entity\Entity;
use pocketmine\Player;

/**
 * Called when a player is interacting an entity
 */
class InteractEvent extends PlayerEvent{
	public static $handlerList = null;

	protected $entity;

	protected $action;

	/**
	 * @param Player   $player
	 */
	public function __construct(Player $player, Entity $entity = null, $action){
		$this->player = $player;
		$this->entity = $entity;
		$this->action = $action;
	}

	/**
	 * @return Entity
	 */
	public function getEntity(){
		return $this->entity;
	}

	/**
	 * @return int
	 */
	public function getAction(){
		return $this->action;
	}
}