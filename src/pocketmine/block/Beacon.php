<?php

/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author SuperXingKong
 * 
 *
 */
 
namespace pocketmine\block;

use pocketmine\block\Block;
use pocketmine\block\Solid;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Tile;
use pocketmine\tile\Beacon as TileBeacon;
use pocketmine\math\Vector3;

 class Beacon extends Transparent implements SolidLight{
 
 	protected $id = self::BEACON;
 
 	public function __construct($meta = 0){
 		$this->meta = $meta;
 	}
 
 	public function canBeActivated() : bool{
 		return true;
 	}
 
 	public function getName() : string{
 		return "Beacon";
 	}
	
	public function getLightLevel(){
		return 15;
	}
 
 	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
 		$this->getLevel()->setBlock($this, $this, true, true);
 		$nbt = new CompoundTag("", [
 			new StringTag("id", Tile::BEACON),
			new ByteTag("isMovable", (bool) false),
			new IntTag("primary", 0),
			new IntTag("secondary", 0),
 			new IntTag("x", $block->x),
 			new IntTag("y", $block->y),
 			new IntTag("z", $block->z)
 		]);
 		$pot = Tile::createTile(Tile::BEACON, $this->getLevel(), $nbt);
 		return true;
 	}
	
	public function onActivate(Item $item, Player $player = null){
 		if($player instanceof Player){
 			$top = $this->getSide(1);
 			if($top->isTransparent() !== true){
				return true;
 			}
 
			$t = $this->getLevel()->getTile($this);
 			$beacon = null;
 			if($t instanceof TileBeacon){
 				$beacon = $t;
 			}else{
 				$nbt = new CompoundTag("", [
 					new StringTag("id", Tile::BEACON),
 					new ByteTag("isMovable", (bool) false),
 					new IntTag("primary", 0),
 					new IntTag("secondary", 0),
 					new IntTag("x", $this->x),
 					new IntTag("y", $this->y),
 					new IntTag("z", $this->z)
 				]);
 				Tile::createTile(Tile::BEACON, $this->getLevel(), $nbt);
 			}
			
			if($player->isCreative() and $player->getServer()->limitedCreative){
				return true;
			}
 				$player->addWindow($beacon->getInventory());
 		}
 
 		return true;
 	}
 }
