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

namespace pocketmine\entity;


use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item as ItemItem;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;

class Zombie extends Monster {
	const NETWORK_ID = 32;

	public $width = 0.6;
	public $length = 0.6;
	public $height = 1.95;

	public $dropExp = [5, 5];

	public $drag = 0.2;
	public $gravity = 0.3;

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Zombie";
	}

	public function initEntity(){
		$this->setMaxHealth(20);
		parent::initEntity();
	}

	/**
	 * @param $currentTick
	 *
	 * @return bool
	 */

	/**
	 * @param Player $player
	 */
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Zombie::NETWORK_ID;
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

	/**
	 * @return array
	 */
	public function getDrops(){
		$cause = $this->lastDamageCause;
		$drops = [];
		if($cause instanceof EntityDamageByEntityEvent){
			$damager = $cause->getDamager();
			if($damager instanceof Player){
				$lootingL = $damager->getItemInHand()->getEnchantmentLevel(Enchantment::TYPE_WEAPON_LOOTING);
				if(mt_rand(0, 199) < (5 + 2 * $lootingL)){
					switch(mt_rand(0, 3)){
						case 0:
							$drops[] = ItemItem::get(ItemItem::IRON_INGOT, 0, 1);
							break;
						case 1:
							$drops[] = ItemItem::get(ItemItem::CARROT, 0, 1);
							break;
						case 2:
							$drops[] = ItemItem::get(ItemItem::POTATO, 0, 1);
							break;
					}
				}
				$count = mt_rand(0, 2 + $lootingL);
				if($count > 0){
					$drops[] = ItemItem::get(ItemItem::ROTTEN_FLESH, 0, $count);
				}
			}
		}

		return $drops;
	}
}
