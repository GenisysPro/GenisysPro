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

use pocketmine\inventory\AnvilInventory;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Player;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class Anvil extends Fallable {

	const NORMAL = 0;
	const SLIGHTLY_DAMAGED = 4;
	const VERY_DAMAGED = 8;

	protected $id = self::ANVIL;

	/**
	 * @return bool
	 */
	public function isSolid(){
		return false;
	}

	/**
	 * Anvil constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return bool
	 */
	public function canBeActivated() : bool{
		return true;
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 5;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 6000;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		$names = [
			self::NORMAL => "Anvil",
			self::SLIGHTLY_DAMAGED => "Slightly Damaged Anvil",
			self::VERY_DAMAGED => "Very Damaged Anvil",
			12 => "Anvil" //just in case somebody uses /give to get an anvil with damage 12 or higher, to prevent crash
		];
		return $names[$this->meta & 0x0c];
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @param Item        $item
	 * @param Player|null $player
	 *
	 * @return bool
	 */
	public function onActivate(Item $item, Player $player = null){
		if(!$this->getLevel()->getServer()->anvilEnabled){
			return true;
		}
		if($player instanceof Player){
			if($player->isCreative() and $player->getServer()->limitedCreative){
				return true;
			}

			$player->addWindow(new AnvilInventory($this));
			$player->craftingType = Player::CRAFTING_ANVIL;
		}

		return true;
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
	 * @return bool|void
	 */
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$direction = ($player !== null ? $player->getDirection() : 0) & 0x03;
		$this->meta = ($this->meta & 0x0c) | $direction;
		$this->getLevel()->setBlock($block, $this, true, true);
		$player->getLevel()->broadcastLevelEvent($player, LevelEventPacket::EVENT_SOUND_ANVIL_FALL);
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 1){
			return [
				[$this->id, $this->meta & 0x0c, 1],
			];
		}else{
			return [];
		}
	}
}
