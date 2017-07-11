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

use pocketmine\network\protocol\ClientboundMapItemDataPacket;
use pocketmine\Server;
use pocketmine\utils\Color;

class Map {

	/**
	 * @var int       $id
	 * @var Color[][] $colors
	 * @var int       $scale
	 * @var int       $width
	 * @var int       $height
	 * @var array     $decorations
	 * @var int       $xOffset
	 * @var int       $yOffset
	 */
	public $id, $colors = [], $scale, $width, $height, $decorations = [], $xOffset, $yOffset;

	/**
	 * Map constructor.
	 *
	 * @param int   $id
	 * @param array $colors
	 * @param int   $scale
	 * @param int   $width
	 * @param int   $height
	 * @param array $decorations
	 * @param int   $xOffset
	 * @param int   $yOffset
	 */
	public function __construct(int $id = -1, array $colors = [], int $scale = 1, int $width = 128, int $height = 128, $decorations = [], int $xOffset = 0, int $yOffset = 0){
		$this->id = $id;
		$this->colors = $colors;
		$this->scale = $scale;
		$this->width = $width;
		$this->height = $height;
		$this->decorations = $decorations;
		$this->xOffset = $xOffset;
		$this->yOffset = $yOffset;
	}

	/**
	 * @return int $id
	 */
	public function getMapId(){
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setMapId(int $id){
		$this->id = $id;
		//TODO: update?? i guess resend.. client would request?
	}

	/**
	 * @return int
	 */
	public function getScale(){
		return $this->scale;
	}

	/**
	 * @param int $scale
	 */
	public function setScale(int $scale){
		$this->scale = $scale;
		$this->update(ClientboundMapItemDataPacket::BITFLAG_TEXTURE_UPDATE);
	}

	/**
	 * @return array
	 */
	public function getDecorations(){
		return $this->decorations;
	}

	/**
	 * @param $decorations
	 *
	 * @return mixed
	 */
	public function addDecoration($decorations){
		$this->decorations[] = $decorations;
		end($this->decorations);
		$this->update(ClientboundMapItemDataPacket::BITFLAG_DECORATION_UPDATE);
		return key($this->decorations);
	}

	/**
	 * @param int $id
	 */
	public function removeDecoration(int $id){
		unset($this->decorations[$id]);
		$this->update(ClientboundMapItemDataPacket::BITFLAG_DECORATION_UPDATE);
	}

	/**
	 * @return int
	 */
	public function getWidth(){
		return $this->width;
	}

	/**
	 * @param int $width
	 */
	public function setWidth(int $width){
		$this->width = $width;
		$this->update(ClientboundMapItemDataPacket::BITFLAG_TEXTURE_UPDATE);
	}

	/**
	 * @return int
	 */
	public function getHeight(){
		return $this->height;
	}

	/**
	 * @param int $height
	 */
	public function setHeight(int $height){
		$this->height = $height;
		$this->update(ClientboundMapItemDataPacket::BITFLAG_TEXTURE_UPDATE);
	}

	/**
	 * @return int
	 */
	public function getXOffset(){
		return $this->xOffset;
	}

	/**
	 * @param int $xOffset
	 */
	public function setXOffset(int $xOffset){
		$this->xOffset = $xOffset;
		$this->update(ClientboundMapItemDataPacket::BITFLAG_TEXTURE_UPDATE);
	}

	/**
	 * @return int
	 */
	public function getYOffset(){
		return $this->yOffset;
	}

	/**
	 * @param int $yOffset
	 */
	public function setYOffset(int $yOffset){
		$this->yOffset = $yOffset;
		$this->update(ClientboundMapItemDataPacket::BITFLAG_TEXTURE_UPDATE);
	}

	/**
	 * @return Color[][]
	 */
	public function getColors(){
		return $this->colors;
	}

	/**
	 * @param array $colors
	 */
	public function setColors(array $colors){
		$this->colors = $colors;
		$this->update(ClientboundMapItemDataPacket::BITFLAG_TEXTURE_UPDATE);
	}

	/**
	 * @param Color $color
	 * @param int   $x
	 * @param int   $y
	 */
	public function setColorAt(Color $color, int $x, int $y){
		$this->colors[$y][$x] = $color;
		$this->update(ClientboundMapItemDataPacket::BITFLAG_TEXTURE_UPDATE);
	}

	/**
	 * @param int  $type
	 * @param null $player
	 */
	public function update($type = 0x00, $player = null){
		$pk = new ClientboundMapItemDataPacket();
		$pk->mapId = $this->getMapId();
		$pk->type = $type;
		$pk->eids = [];
		$pk->scale = $this->getScale();
		$pk->decorations = $this->getDecorations();
		$pk->width = $this->getWidth();
		$pk->height = $this->getHeight();
		$pk->xOffset = $this->getXOffset();
		$pk->yOffset = $this->getYOffset();
		$pk->colors = $this->getColors();
		if($player === null){
			Server::getInstance()->broadcastPacket(Server::getInstance()->getOnlinePlayers(), $pk);
		}else{
			$player->dataPacket($pk);
		}
	}

	/**
	 * @param String $path
	 */
	public function fromPng(String $path){

		$img = imagecreatefrompng($path);
		$this->width = $width = imagesx($img);
		$this->height = $height = imagesy($img);
		$colors = [];
		for($y = 0; $y < $height; $y++){
			for($x = 0; $x < $width; $x++){
				$r = 0;
				$g = 0;
				$b = 0;
				$rgb = imagecolorat($img, $x, $y);
				$rgba = imagecolorsforindex($img, $rgb);
				$r += $rgba['red'];
				$g += $rgba['green'];
				$b += $rgba['blue'];
				if(($r + $g + $b) === 0){
					$colors[$y][$x] = new Color(255, 255, 255, 0xff);
				}else{
					$colors[$y][$x] = new Color($r, $g, $b, 0xff);
				}
			}
		}
		$this->colors = $colors;

	}

	public function save(){
		//TODO.
	}
}