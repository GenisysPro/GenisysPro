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
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Portal extends Transparent {

	protected $id = self::PORTAL;

	/** @var  Vector3 */
	private $temporalVector = null;

	/**
	 * Portal constructor.
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
		if($this->temporalVector === null){
			$this->temporalVector = new Vector3(0, 0, 0);
		}
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Portal";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return -1;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 0;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return bool
	 */
	public function canPassThrough(){
		return true;
	}

	/**
	 * @return bool
	 */
	public function hasEntityCollision(){
		return true;
	}

	/**
	 * @param Item $item
	 *
	 * @return mixed|void
	 */
	public function onBreak(Item $item){
		$block = $this;
		if($this->getLevel()->getBlock($this->temporalVector->setComponents($block->x - 1, $block->y, $block->z))->getId() == Block::PORTAL or
			$this->getLevel()->getBlock($this->temporalVector->setComponents($block->x + 1, $block->y, $block->z))->getId() == Block::PORTAL
		){//x方向
			for($x = $block->x; $this->getLevel()->getBlock($this->temporalVector->setComponents($x, $block->y, $block->z))->getId() == Block::PORTAL; $x++){
				for($y = $block->y; $this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y, $block->z))->getId() == Block::PORTAL; $y++){
					$this->getLevel()->setBlock($this->temporalVector->setComponents($x, $y, $block->z), new Air());
				}
				for($y = $block->y - 1; $this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y, $block->z))->getId() == Block::PORTAL; $y--){
					$this->getLevel()->setBlock($this->temporalVector->setComponents($x, $y, $block->z), new Air());
				}
			}
			for($x = $block->x - 1; $this->getLevel()->getBlock($this->temporalVector->setComponents($x, $block->y, $block->z))->getId() == Block::PORTAL; $x--){
				for($y = $block->y; $this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y, $block->z))->getId() == Block::PORTAL; $y++){
					$this->getLevel()->setBlock($this->temporalVector->setComponents($x, $y, $block->z), new Air());
				}
				for($y = $block->y - 1; $this->getLevel()->getBlock($this->temporalVector->setComponents($x, $y, $block->z))->getId() == Block::PORTAL; $y--){
					$this->getLevel()->setBlock($this->temporalVector->setComponents($x, $y, $block->z), new Air());
				}
			}
		}else{//z方向
			for($z = $block->z; $this->getLevel()->getBlock($this->temporalVector->setComponents($block->x, $block->y, $z))->getId() == Block::PORTAL; $z++){
				for($y = $block->y; $this->getLevel()->getBlock($this->temporalVector->setComponents($block->x, $y, $z))->getId() == Block::PORTAL; $y++){
					$this->getLevel()->setBlock($this->temporalVector->setComponents($block->x, $y, $z), new Air());
				}
				for($y = $block->y - 1; $this->getLevel()->getBlock($this->temporalVector->setComponents($block->x, $y, $z))->getId() == Block::PORTAL; $y--){
					$this->getLevel()->setBlock($this->temporalVector->setComponents($block->x, $y, $z), new Air());
				}
			}
			for($z = $block->z - 1; $this->getLevel()->getBlock($this->temporalVector->setComponents($block->x, $block->y, $z))->getId() == Block::PORTAL; $z--){
				for($y = $block->y; $this->getLevel()->getBlock($this->temporalVector->setComponents($block->x, $y, $z))->getId() == Block::PORTAL; $y++){
					$this->getLevel()->setBlock($this->temporalVector->setComponents($block->x, $y, $z), new Air());
				}
				for($y = $block->y - 1; $this->getLevel()->getBlock($this->temporalVector->setComponents($block->x, $y, $z))->getId() == Block::PORTAL; $y--){
					$this->getLevel()->setBlock($this->temporalVector->setComponents($block->x, $y, $z), new Air());
				}
			}
		}
		parent::onBreak($item);
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
		if($player instanceof Player){
			$this->meta = $player->getDirection() & 0x01;
		}
		$this->getLevel()->setBlock($block, $this, true, true);

		return true;
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		return [];
	}
}