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

declare(strict_types=1);

namespace pocketmine\level\format;

class SubChunk {

	protected $ids;
	protected $data;
	protected $blockLight;
	protected $skyLight;

	/**
	 * @param        $target
	 * @param        $data
	 * @param        $length
	 * @param string $value
	 */
	private static function assignData(&$target, $data, $length, $value = "\x00"){
		if(strlen($data) !== $length){
			assert($data === "", "Invalid non-zero length given, expected $length, got " . strlen($data));
			$target = str_repeat($value, $length);
		}else{
			$target = $data;
		}
	}

	/**
	 * SubChunk constructor.
	 *
	 * @param string $ids
	 * @param string $data
	 * @param string $skyLight
	 * @param string $blockLight
	 */
	public function __construct(string $ids = "", string $data = "", string $skyLight = "", string $blockLight = ""){
		self::assignData($this->ids, $ids, 4096);
		self::assignData($this->data, $data, 2048);
		self::assignData($this->skyLight, $skyLight, 2048, "\xff");
		self::assignData($this->blockLight, $blockLight, 2048);
	}

	/**
	 * @return bool
	 */
	public function isEmpty() : bool{
		assert(strlen($this->ids) === 4096, "Wrong length of ID array, expecting 4096 bytes, got " . strlen($this->ids));
		return substr_count($this->ids, "\x00") === 4096;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getBlockId(int $x, int $y, int $z) : int{
		return ord($this->ids{($x << 8) | ($z << 4) | $y});
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $id
	 *
	 * @return bool
	 */
	public function setBlockId(int $x, int $y, int $z, int $id) : bool{
		$this->ids{($x << 8) | ($z << 4) | $y} = chr($id);
		return true;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getBlockData(int $x, int $y, int $z) : int{
		$m = ord($this->data{($x << 7) + ($z << 3) + ($y >> 1)});
		if(($y & 1) === 0){
			return $m & 0x0f;
		}else{
			return $m >> 4;
		}
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $data
	 *
	 * @return bool
	 */
	public function setBlockData(int $x, int $y, int $z, int $data) : bool{
		$i = ($x << 7) | ($z << 3) | ($y >> 1);
		if(($y & 1) === 0){
			$this->data{$i} = chr((ord($this->data{$i}) & 0xf0) | ($data & 0x0f));
		}else{
			$this->data{$i} = chr((($data & 0x0f) << 4) | (ord($this->data{$i}) & 0x0f));
		}
		return true;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getFullBlock(int $x, int $y, int $z) : int{
		$i = ($x << 8) | ($z << 4) | $y;
		if(($y & 1) === 0){
			return (ord($this->ids{$i}) << 4) | (ord($this->data{$i >> 1}) & 0x0f);
		}else{
			return (ord($this->ids{$i}) << 4) | (ord($this->data{$i >> 1}) >> 4);
		}
	}

	/**
	 * @param int  $x
	 * @param int  $y
	 * @param int  $z
	 * @param null $id
	 * @param null $data
	 *
	 * @return bool
	 */
	public function setBlock(int $x, int $y, int $z, $id = null, $data = null) : bool{
		$i = ($x << 8) | ($z << 4) | $y;
		$changed = false;
		if($id !== null){
			$block = chr($id);
			if($this->ids{$i} !== $block){
				$this->ids{$i} = $block;
				$changed = true;
			}
		}

		if($data !== null){
			$i >>= 1;
			$byte = ord($this->data{$i});
			if(($y & 1) === 0){
				$this->data{$i} = chr(($byte & 0xf0) | ($data & 0x0f));
			}else{
				$this->data{$i} = chr((($data & 0x0f) << 4) | ($byte & 0x0f));
			}
			if($this->data{$i} !== $byte){
				$changed = true;
			}
		}

		return $changed;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getBlockLight(int $x, int $y, int $z) : int{
		$byte = ord($this->blockLight{($x << 7) + ($z << 3) + ($y >> 1)});
		if(($y & 1) === 0){
			return $byte & 0x0f;
		}else{
			return $byte >> 4;
		}
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $level
	 *
	 * @return bool
	 */
	public function setBlockLight(int $x, int $y, int $z, int $level) : bool{
		$i = ($x << 7) + ($z << 3) + ($y >> 1);
		$byte = ord($this->blockLight{$i});
		if(($y & 1) === 0){
			$this->blockLight{$i} = chr(($byte & 0xf0) | ($level & 0x0f));
		}else{
			$this->blockLight{$i} = chr((($level & 0x0f) << 4) | ($byte & 0x0f));
		}
		return true;
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int
	 */
	public function getBlockSkyLight(int $x, int $y, int $z) : int{
		$byte = ord($this->skyLight{($x << 7) + ($z << 3) + ($y >> 1)});
		if(($y & 1) === 0){
			return $byte & 0x0f;
		}else{
			return $byte >> 4;
		}
	}

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $level
	 *
	 * @return bool
	 */
	public function setBlockSkyLight(int $x, int $y, int $z, int $level) : bool{
		$i = ($x << 7) + ($z << 3) + ($y >> 1);
		$byte = ord($this->skyLight{$i});
		if(($y & 1) === 0){
			$this->skyLight{$i} = chr(($byte & 0xf0) | ($level & 0x0f));
		}else{
			$this->skyLight{$i} = chr((($level & 0x0f) << 4) | ($byte & 0x0f));
		}
		return true;
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return int
	 */
	public function getHighestBlockAt(int $x, int $z) : int{
		for($y = 15; $y >= 0; --$y){
			if($this->ids{($x << 8) | ($z << 4) | $y} !== "\x00"){
				return $y;
			}
		}

		return -1; //highest block not in this subchunk
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return string
	 */
	public function getBlockIdColumn(int $x, int $z) : string{
		return substr($this->ids, (($x << 8) | ($z << 4)), 16);
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return string
	 */
	public function getBlockDataColumn(int $x, int $z) : string{
		return substr($this->data, (($x << 7) | ($z << 3)), 8);
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return string
	 */
	public function getBlockLightColumn(int $x, int $z) : string{
		return substr($this->blockLight, (($x << 7) | ($z << 3)), 8);
	}

	/**
	 * @param int $x
	 * @param int $z
	 *
	 * @return string
	 */
	public function getSkyLightColumn(int $x, int $z) : string{
		return substr($this->skyLight, (($x << 7) | ($z << 3)), 8);
	}

	/**
	 * @return string
	 */
	public function getBlockIdArray() : string{
		assert(strlen($this->ids) === 4096, "Wrong length of ID array, expecting 4096 bytes, got " . strlen($this->ids));
		return $this->ids;
	}

	/**
	 * @return string
	 */
	public function getBlockDataArray() : string{
		assert(strlen($this->data) === 2048, "Wrong length of data array, expecting 2048 bytes, got " . strlen($this->data));
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function getSkyLightArray() : string{
		assert(strlen($this->skyLight) === 2048, "Wrong length of skylight array, expecting 2048 bytes, got " . strlen($this->skyLight));
		return $this->skyLight;
	}

	/**
	 * @return string
	 */
	public function getBlockLightArray() : string{
		assert(strlen($this->blockLight) === 2048, "Wrong length of light array, expecting 2048 bytes, got " . strlen($this->blockLight));
		return $this->blockLight;
	}

	/**
	 * @return string
	 */
	public function networkSerialize() : string{
		// storage version, ids, data, skylight, blocklight
		return "\x00" . $this->ids . $this->data . $this->skyLight . $this->blockLight;
	}

	/**
	 * @return string
	 */
	public function fastSerialize() : string{
		return
			$this->ids .
			$this->data .
			$this->skyLight .
			$this->blockLight;
	}

	/**
	 * @param string $data
	 *
	 * @return SubChunk
	 */
	public static function fastDeserialize(string $data) : SubChunk{
		return new SubChunk(
			substr($data, 0, 4096), //ids
			substr($data, 4096, 2048), //data
			substr($data, 6144, 2048), //sky light
			substr($data, 8192, 2048)  //block light
		);
	}
}