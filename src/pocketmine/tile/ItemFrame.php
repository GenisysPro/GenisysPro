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

namespace pocketmine\tile;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class ItemFrame extends Spawnable {

	public $map_uuid = -1;

	/**
	 * ItemFrame constructor.
	 *
	 * @param Level       $level
	 * @param CompoundTag $nbt
	 */
	public function __construct(Level $level, CompoundTag $nbt){
		if(!isset($nbt->ItemRotation)){
			$nbt->ItemRotation = new ByteTag("ItemRotation", 0);
		}

		if(!isset($nbt->ItemDropChance)){
			$nbt->ItemDropChance = new FloatTag("ItemDropChance", 1.0);
		}

		parent::__construct($level, $nbt);
	}

	/**
	 * @return bool
	 */
	public function hasItem() : bool{
		return $this->getItem()->getId() !== Item::AIR;
	}

	/**
	 * @return Item
	 */
	public function getItem() : Item{
		if(isset($this->namedtag->Item)){
			return Item::nbtDeserialize($this->namedtag->Item);
		}else{
			return Item::get(Item::AIR);
		}
	}

	/**
	 * @param Item|null $item
	 */
	public function setItem(Item $item = null){
		if($item !== null and $item->getId() !== Item::AIR){
			$this->namedtag->Item = $item->nbtSerialize(-1, "Item");
		}else{
			unset($this->namedtag->Item);
		}
		$this->onChanged();
	}

	/**
	 * @return int
	 */
	public function getItemRotation() : int{
		return $this->namedtag->ItemRotation->getValue();
	}

	/**
	 * @param int $rotation
	 */
	public function setItemRotation(int $rotation){
		$this->namedtag->ItemRotation = new ByteTag("ItemRotation", $rotation);
		$this->onChanged();
	}

	/**
	 * @return float
	 */
	public function getItemDropChance() : float{
		return $this->namedtag->ItemDropChance->getValue();
	}

	/**
	 * @param float $chance
	 */
	public function setItemDropChance(float $chance){
		$this->namedtag->ItemDropChance = new FloatTag("ItemDropChance", $chance);
		$this->onChanged();
	}

	/**
	 * @param string $mapid
	 */
	public function SetMapID(string $mapid){
		$this->map_uuid = $mapid;
		$this->namedtag->Map_UUID = new StringTag("map_uuid", $mapid);
		$this->onChanged();
	}

	/**
	 * @return string
	 */
	public function getMapID() : string{
		return $this->map_uuid;
	}

	/**
	 * @return CompoundTag
	 */
	public function getSpawnCompound(){
		$tag = new CompoundTag("", [
			new StringTag("id", Tile::ITEM_FRAME),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z),
			$this->namedtag->ItemDropChance,
			$this->namedtag->ItemRotation,
		]);
		if($this->hasItem()){
			$tag->Item = $this->namedtag->Item;
			if($this->getItem()->getId() === Item::FILLED_MAP){
				if(isset($this->namedtag->Map_UUID)){
					$tag->Map_UUID = $this->namedtag->Map_UUID;
				}
			}
		}

		return $tag;
	}

}