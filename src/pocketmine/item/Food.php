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

namespace pocketmine\item;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityEatItemEvent;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\Player;

abstract class Food extends Item implements FoodSource {
	/**
	 * @return bool
	 */
	public function canBeConsumed() : bool{
		return true;
	}

	/**
	 * @param Entity $entity
	 *
	 * @return bool
	 */
	public function canBeConsumedBy(Entity $entity) : bool{
		return $entity instanceof Player and ($entity->getFood() < $entity->getMaxFood()) and $this->canBeConsumed();
	}

	/**
	 * @return Food|Item
	 */
	public function getResidue(){
		if($this->getCount() === 1){
			return Item::get(0);
		}else{
			$new = clone $this;
			$new->count--;
			return $new;
		}
	}

	/**
	 * @return array
	 */
	public function getAdditionalEffects() : array{
		return [];
	}

	/**
	 * @param Entity $human
	 */
	public function onConsume(Entity $human){
		$pk = new EntityEventPacket();
		$pk->eid = $human->getId();
		$pk->event = EntityEventPacket::USE_ITEM;
		if($human instanceof Player){
			$human->dataPacket($pk);
		}
		$human->getLevel()->getServer()->broadcastPacket($human->getViewers(), $pk);

		$human->getLevel()->getServer()->getPluginManager()->callEvent($ev = new EntityEatItemEvent($human, $this));

		$human->addSaturation($ev->getSaturationRestore());
		$human->addFood($ev->getFoodRestore());
		foreach($ev->getAdditionalEffects() as $effect){
			$human->addEffect($effect);
		}

		$human->getInventory()->setItemInHand($ev->getResidue());
	}
}
