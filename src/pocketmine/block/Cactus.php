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

namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\event\block\BlockGrowEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;

class Cactus extends Transparent {

	protected $id = self::CACTUS;

	/**
	 * Cactus constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 0.4;
	}

	/**
	 * @return bool
	 */
	public function hasEntityCollision(){
		return true;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Cactus";
	}

	/**
	 * @return AxisAlignedBB
	 */
	protected function recalculateBoundingBox(){

		return new AxisAlignedBB(
			$this->x + 0.0625,
			$this->y + 0.0625,
			$this->z + 0.0625,
			$this->x + 0.9375,
			$this->y + 0.9375,
			$this->z + 0.9375
		);
	}

	/**
	 * @param Entity $entity
	 */
	public function onEntityCollide(Entity $entity){
		$ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_CONTACT, 1);
		if($entity->attack($ev->getFinalDamage(), $ev) === true){
			$ev->useArmors();
		}
	}

	/**
	 * @param int $type
	 *
	 * @return bool
	 */
	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$down = $this->getSide(0);
			if($down->getId() !== self::SAND and $down->getId() !== self::CACTUS){
				$this->getLevel()->useBreakOn($this);
			}else{
				for($side = 2; $side <= 5; ++$side){
					$b = $this->getSide($side);
					if(!$b->canBeFlowedInto()){
						$this->getLevel()->useBreakOn($this);
					}
				}
			}
		}elseif($type === Level::BLOCK_UPDATE_RANDOM){
			if($this->getSide(0)->getId() !== self::CACTUS){
				if($this->meta == 0x0F){
					for($y = 1; $y < 3; ++$y){
						$b = $this->getLevel()->getBlock(new Vector3($this->x, $this->y + $y, $this->z));
						if($b->getId() === self::AIR){
							Server::getInstance()->getPluginManager()->callEvent($ev = new BlockGrowEvent($b, new Cactus()));
							if(!$ev->isCancelled()){
								$this->getLevel()->setBlock($b, $ev->getNewState(), true);
							}
						}
					}
					$this->meta = 0;
					$this->getLevel()->setBlock($this, $this);
				}else{
					++$this->meta;
					$this->getLevel()->setBlock($this, $this);
				}
			}
		}

		return false;
	}

	/**
	 * @param Item        $item
	 * @param Block       $block
	 * @param Block       $target
	 * @param int         $face
	 * @param float       $fx
	 * @param float       $fy
	 * @param float       $fz
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() === self::SAND or $down->getId() === self::CACTUS){
			$block0 = $this->getSide(2);
			$block1 = $this->getSide(3);
			$block2 = $this->getSide(4);
			$block3 = $this->getSide(5);
			if($block0->isTransparent() === true and $block1->isTransparent() === true and $block2->isTransparent() === true and $block3->isTransparent() === true){
				$this->getLevel()->setBlock($this, $this, true);

				return true;
			}
		}

		return false;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [
			[$this->id, 0, 1],
		];
	}
}