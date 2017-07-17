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

use pocketmine\inventory\EnchantInventory;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\math\AxisAlignedBB;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;

class EnchantingTable extends Transparent {

	protected $id = self::ENCHANTING_TABLE;

	/**
	 * EnchantingTable constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return int
	 */
	public function getLightLevel(){
		return 12;
	}

	/**
	 * @return AxisAlignedBB
	 */
	public function getBoundingBox(){
		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 0.75,
			$this->z + 1
		);
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
		$this->getLevel()->setBlock($block, $this, true, true);
		$nbt = new CompoundTag("", [
			new StringTag("id", Tile::ENCHANT_TABLE),
			new IntTag("x", $this->x),
			new IntTag("y", $this->y),
			new IntTag("z", $this->z)
		]);

		if($item->hasCustomName()){
			$nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
		}

		if($item->hasCustomBlockData()){
			foreach($item->getCustomBlockData() as $key => $v){
				$nbt->{$key} = $v;
			}
		}

		Tile::createTile(Tile::ENCHANT_TABLE, $this->getLevel(), $nbt);

		return true;
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
		return "Enchanting Table";
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
		if(!$this->getLevel()->getServer()->enchantingTableEnabled){
			return true;
		}
		if($player instanceof Player){
			if($player->isCreative() and $player->getServer()->limitedCreative){
				return true;
			}
			$enchantTable = null;
				$this->getLevel()->setBlock($this, $this, true, true);
				$nbt = new CompoundTag("", [
					new StringTag("id", Tile::ENCHANT_TABLE),
					new IntTag("x", $this->x),
					new IntTag("y", $this->y),
					new IntTag("z", $this->z)
				]);

				if($item->hasCustomName()){
					$nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
				}

				if($item->hasCustomBlockData()){
					foreach($item->getCustomBlockData() as $key => $v){
						$nbt->{$key} = $v;
					}
				}

				Tile::createTile(Tile::ENCHANT_TABLE, $this->getLevel(), $nbt);
			}
			$player->addWindow(new EnchantInventory($this));
			$player->craftingType = Player::CRAFTING_ENCHANT;


		return true;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 1){
			return [
				[$this->id, 0, 1],
			];
		}else{
			return [];
		}
	}
}