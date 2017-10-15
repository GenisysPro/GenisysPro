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
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\entity;

use pocketmine\level\Level;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;

class Ocelot extends Animal {
	const NETWORK_ID = 22;

	const DATA_CAT_TYPE = 18;

	const TYPE_WILD = 0;
	const TYPE_TUXEDO = 1;
	const TYPE_TABBY = 2;
	const TYPE_SIAMESE = 3;

	public $width = 0.312;
	public $length = 2.188;
	public $height = 0;

	public $dropExp = [1, 3];

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Ocelot";
	}

	/**
	 * Ocelot constructor.
	 *
	 * @param Level       $level
	 * @param CompoundTag $nbt
	 */
	public function __construct(Level $level, CompoundTag $nbt){
		if(!isset($nbt->CatType)){
			$nbt->CatType = new ByteTag("CatType", mt_rand(0, 3));
		}
		parent::__construct($level, $nbt);

		$this->setDataProperty(self::DATA_CAT_TYPE, self::DATA_TYPE_BYTE, $this->getCatType());
	}

	/**
	 * @param int $type
	 */
	public function setCatType(int $type){
		$this->namedtag->CatType = new ByteTag("CatType", $type);
	}

	/**
	 * @return int
	 */
	public function getCatType() : int{
		return (int) $this->namedtag["CatType"];
	}

	/**
	 * @param Player $player
	 */
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = self::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}
}
