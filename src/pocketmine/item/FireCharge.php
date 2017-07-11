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

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\Portal;
use pocketmine\block\Solid;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\math\Vector3;

class FireCharge extends Item {
	/** @var Vector3 */
	private $temporalVector = null;

	/**
	 * FireCharge constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::FIRE_CHARGE, $meta, $count, "Fire Charge");
		if($this->temporalVector === null){
			$this->temporalVector = new Vector3(0, 0, 0);
		}
	}

	/**
	 * @return bool
	 */
	public function canBeActivated() : bool{
		return true;
	}

	/**
	 * @param Level  $level
	 * @param Player $player
	 * @param Block  $block
	 * @param Block  $target
	 * @param        $face
	 * @param        $fx
	 * @param        $fy
	 * @param        $fz
	 *
	 * @return bool
	 */
	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
		if($target->getId() === Block::OBSIDIAN and $player->getServer()->netherEnabled){
			$tx = $target->getX();
			$ty = $target->getY();
			$tz = $target->getZ();
			$x_max = $tx;
			$x_min = $tx;
			for($x = $tx + 1; $level->getBlock($this->temporalVector->setComponents($x, $ty, $tz))->getId() == Block::OBSIDIAN; $x++){
				$x_max++;
			}
			for($x = $tx - 1; $level->getBlock($this->temporalVector->setComponents($x, $ty, $tz))->getId() == Block::OBSIDIAN; $x--){
				$x_min--;
			}
			$count_x = $x_max - $x_min + 1;
			if($count_x >= 4 and $count_x <= 23){
				$x_max_y = $ty;
				$x_min_y = $ty;
				for($y = $ty; $level->getBlock($this->temporalVector->setComponents($x_max, $y, $tz))->getId() == Block::OBSIDIAN; $y++){
					$x_max_y++;
				}
				for($y = $ty; $level->getBlock($this->temporalVector->setComponents($x_min, $y, $tz))->getId() == Block::OBSIDIAN; $y++){
					$x_min_y++;
				}
				$y_max = min($x_max_y, $x_min_y) - 1;
				$count_y = $y_max - $ty + 2;
				if($count_y >= 5 and $count_y <= 23){
					$count_up = 0;
					for($ux = $x_min; ($level->getBlock($this->temporalVector->setComponents($ux, $y_max, $tz))->getId() == Block::OBSIDIAN and $ux <= $x_max); $ux++){
						$count_up++;
					}
					if($count_up == $count_x){
						for($px = $x_min + 1; $px < $x_max; $px++){
							for($py = $ty + 1; $py < $y_max; $py++){
								$level->setBlock($this->temporalVector->setComponents($px, $py, $tz), new Portal());
							}
						}
						if($player->isSurvival()){
							$this->useOn($block, 2);
							$player->getInventory()->setItemInHand($this);
						}
						return true;
					}
				}
			}

			$z_max = $tz;
			$z_min = $tz;
			for($z = $tz + 1; $level->getBlock($this->temporalVector->setComponents($tx, $ty, $z))->getId() == Block::OBSIDIAN; $z++){
				$z_max++;
			}
			for($z = $tz - 1; $level->getBlock($this->temporalVector->setComponents($tx, $ty, $z))->getId() == Block::OBSIDIAN; $z--){
				$z_min--;
			}
			$count_z = $z_max - $z_min + 1;
			if($count_z >= 4 and $count_z <= 23){
				$z_max_y = $ty;
				$z_min_y = $ty;
				for($y = $ty; $level->getBlock($this->temporalVector->setComponents($tx, $y, $z_max))->getId() == Block::OBSIDIAN; $y++){
					$z_max_y++;
				}
				for($y = $ty; $level->getBlock($this->temporalVector->setComponents($tx, $y, $z_min))->getId() == Block::OBSIDIAN; $y++){
					$z_min_y++;
				}
				$y_max = min($z_max_y, $z_min_y) - 1;
				$count_y = $y_max - $ty + 2;
				if($count_y >= 5 and $count_y <= 23){
					$count_up = 0;
					for($uz = $z_min; ($level->getBlock($this->temporalVector->setComponents($tx, $y_max, $uz))->getId() == Block::OBSIDIAN and $uz <= $z_max); $uz++){
						$count_up++;
					}
					if($count_up == $count_z){
						for($pz = $z_min + 1; $pz < $z_max; $pz++){
							for($py = $ty + 1; $py < $y_max; $py++){
								$level->setBlock($this->temporalVector->setComponents($tx, $py, $pz), new Portal());
							}
						}
						if($player->isSurvival()){
							$this->useOn($block, 2);
							$player->getInventory()->setItemInHand($this);
						}
						return true;
					}
				}
			}
		}

		if($block->getId() === self::AIR and ($target instanceof Solid)){
			$level->setBlock($block, new Fire(), true);

			/** @var Fire $block */
			$block = $level->getBlock($block);
			if($block->getSide(Vector3::SIDE_DOWN)->isTopFacingSurfaceSolid() or $block->canNeighborBurn()){
				$level->scheduleUpdate($block, $block->getTickRate() + mt_rand(0, 10));
			}

			if($player->isSurvival()){
				$this->useOn($block, 2);
				$player->getInventory()->setItemInHand($this);
			}

			return true;
		}

		return false;
	}
}