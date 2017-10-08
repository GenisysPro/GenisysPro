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

namespace pocketmine\network\protocol;

#include <rules/DataPacket.h>

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\Utils;

abstract class DataPacket extends BinaryStream {

	const NETWORK_ID = 0;

	public $isEncoded = false;

	/**
	 * @return int
	 */
	public function pid(){
		return $this::NETWORK_ID;
	}

	/**
	 * @return mixed
	 */
	abstract public function encode();

	/**
	 * @return mixed
	 */
	abstract public function decode();

	public function reset(){
		$this->buffer = Binary::writeUnsignedVarInt(static::NETWORK_ID);
		$this->offset = 0;
	}

	/**
	 * @return $this
	 */
	public function clean(){
		$this->buffer = null;
		$this->isEncoded = false;
		$this->offset = 0;

		return $this;
	}

	/**
	 * @return array
	 */
	public function __debugInfo(){
		$data = [];
		foreach($this as $k => $v){
			if($k === "buffer"){
				$data[$k] = bin2hex($v);
			}elseif(is_string($v) or (is_object($v) and method_exists($v, "__toString"))){
				$data[$k] = Utils::printable((string) $v);
			}else{
				$data[$k] = $v;
			}
		}

		return $data;
	}

	/**
	 * @param bool $types
	 *
	 * @return array
	 */
	public function getEntityMetadata(bool $types = true) : array{
		$count = $this->getUnsignedVarInt();
		$data = [];
		for($i = 0; $i < $count; ++$i){
			$key = $this->getUnsignedVarInt();
			$type = $this->getUnsignedVarInt();
			$value = null;
			switch($type){
				case Entity::DATA_TYPE_BYTE:
					$value = $this->getByte();
					break;
				case Entity::DATA_TYPE_SHORT:
					$value = $this->getLShort(true); //signed
					break;
				case Entity::DATA_TYPE_INT:
					$value = $this->getVarInt();
					break;
				case Entity::DATA_TYPE_FLOAT:
					$value = $this->getLFloat();
					break;
				case Entity::DATA_TYPE_STRING:
					$value = $this->getString();
					break;
				case Entity::DATA_TYPE_SLOT:
					//TODO: use objects directly
					$value = [];
					$item = $this->getSlot();
					$value[0] = $item->getId();
					$value[1] = $item->getCount();
					$value[2] = $item->getDamage();
					break;
				case Entity::DATA_TYPE_POS:
					$value = [];
					$value[0] = $this->getVarInt(); //x
					$value[1] = $this->getVarInt(); //y (SIGNED)
					$value[2] = $this->getVarInt(); //z
					break;
				case Entity::DATA_TYPE_LONG:
					$value = $this->getVarInt(); //TODO: varint64 proper support
					break;
				case Entity::DATA_TYPE_VECTOR3F:
					$value = [0.0, 0.0, 0.0];
					$this->getVector3f($value[0], $value[1], $value[2]);
					break;
				default:
					$value = [];
			}
			if($types === true){
				$data[$key] = [$value, $type];
			}else{
				$data[$key] = $value;
			}
		}

		return $data;
	}

	/**
	 * @param array $metadata
	 */
	public function putEntityMetadata(array $metadata){
		$this->putUnsignedVarInt(count($metadata));
		foreach($metadata as $key => $d){
			$this->putUnsignedVarInt($key); //data key
			$this->putUnsignedVarInt($d[0]); //data type
			switch($d[0]){
				case Entity::DATA_TYPE_BYTE:
					$this->putByte($d[1]);
					break;
				case Entity::DATA_TYPE_SHORT:
					$this->putLShort($d[1]); //SIGNED short!
					break;
				case Entity::DATA_TYPE_INT:
					$this->putVarInt($d[1]);
					break;
				case Entity::DATA_TYPE_FLOAT:
					$this->putLFloat($d[1]);
					break;
				case Entity::DATA_TYPE_STRING:
					$this->putString($d[1]);
					break;
				case Entity::DATA_TYPE_SLOT:
					//TODO: change this implementation (use objects)
					$this->putSlot(Item::get($d[1][0], $d[1][2], $d[1][1])); //ID, damage, count
					break;
				case Entity::DATA_TYPE_POS:
					//TODO: change this implementation (use objects)
					$this->putVarInt($d[1][0]); //x
					$this->putVarInt($d[1][1]); //y (SIGNED)
					$this->putVarInt($d[1][2]); //z
					break;
				case Entity::DATA_TYPE_LONG:
					$this->putVarInt($d[1]); //TODO: varint64 support
					break;
				case Entity::DATA_TYPE_VECTOR3F:
					//TODO: change this implementation (use objects)
					$this->putVector3f($d[1][0], $d[1][1], $d[1][2]); //x, y, z
			}
		}
	}

	/**
	 * Reads an block position with unsigned Y coordinate.
	 * @param int &$x
	 * @param int &$y
	 * @param int &$z
	 */
	public function getBlockPosition(&$x, &$y, &$z){
		$x = $this->getVarInt();
		$y = $this->getUnsignedVarInt();
		$z = $this->getVarInt();
	}

	/**
	 * Writes a block position with unsigned Y coordinate.
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 */
	public function putBlockPosition(int $x, int $y, int $z){
		$this->putVarInt($x);
		$this->putUnsignedVarInt($y);
		$this->putVarInt($z);
	}

	/**
	 * Reads a block position with a signed Y coordinate.
	 * @param int &$x
	 * @param int &$y
	 * @param int &$z
	 */
	public function getSignedBlockPosition(&$x, &$y, &$z){
		$x = $this->getVarInt();
		$y = $this->getVarInt();
		$z = $this->getVarInt();
	}

	/**
	 * Writes a block position with a signed Y coordinate.
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 */
	public function putSignedBlockPosition(int $x, int $y, int $z){
		$this->putVarInt($x);
		$this->putVarInt($y);
		$this->putVarInt($z);
	}

	/**
	 * Reads a floating-point vector3 rounded to 4dp.
	 * @param float $x
	 * @param float $y
	 * @param float $z
	 */
	public function getVector3f(&$x, &$y, &$z){
		$x = $this->getLFloat(4);
		$y = $this->getLFloat(4);
		$z = $this->getLFloat(4);
	}

	/**
	 * Writes a floating-point vector3
	 * @param float $x
	 * @param float $y
	 * @param float $z
	 */
	public function putVector3f(float $x, float $y, float $z){
		$this->putLFloat($x);
		$this->putLFloat($y);
		$this->putLFloat($z);
	}

	/**
	 * Reads a floating-point Vector3 object
	 * TODO: get rid of primitive methods and replace with this
	 *
	 * @return Vector3
	 */
	public function getVector3Obj() : Vector3{
		return new Vector3(
			$this->getLFloat(4),
			$this->getLFloat(4),
			$this->getLFloat(4)
		);
	}

	/**
	 * Writes a floating-point Vector3 object, or 3x zero if null is given.
	 *
	 * Note: ONLY use this where it is reasonable to allow not specifying the vector.
	 * For all other purposes, use {@link DataPacket#putVector3Obj}
	 *
	 * @param Vector3|null $vector
	 */
	public function putVector3ObjNullable(Vector3 $vector = null){
		if($vector){
			$this->putVector3Obj($vector);
		}else{
			$this->putLFloat(0.0);
			$this->putLFloat(0.0);
			$this->putLFloat(0.0);
		}
	}

	/**
	 * Writes a floating-point Vector3 object
	 * TODO: get rid of primitive methods and replace with this
	 *
	 * @param Vector3 $vector
	 */
	public function putVector3Obj(Vector3 $vector){
		$this->putLFloat($vector->x);
		$this->putLFloat($vector->y);
		$this->putLFloat($vector->z);
	}

	public function getByteRotation() : float{
		return (float) ($this->getByte() * (360 / 256));
	}

	public function putByteRotation(float $rotation){
		$this->putByte((int) ($rotation / (360 / 256)));
	}

	/**
	 * Reads gamerules
	 * TODO: implement this properly
	 *
	 * @return array
	 */
	public function getGameRules() : array{
		$count = $this->getUnsignedVarInt();
		$rules = [];
		for($i = 0; $i < $count; ++$i){
			$name = $this->getString();
			$type = $this->getUnsignedVarInt();
			$value = null;
			switch($type){
				case 1:
					$value = $this->getBool();
					break;
				case 2:
					$value = $this->getUnsignedVarInt();
					break;
				case 3:
					$value = $this->getLFloat();
					break;
			}

			$rules[$name] = [$type, $value];
		}

		return $rules;
	}

	/**
	 * Writes a gamerule array
	 * TODO: implement this properly
	 *
	 * @param array $rules
	 */
	public function putGameRules(array $rules){
		$this->putUnsignedVarInt(count($rules));
		foreach($rules as $name => $rule){
			$this->putString($name);
			$this->putUnsignedVarInt($rule[0]);
			switch($rule[0]){
				case 1:
					$this->putBool($rule[1]);
					break;
				case 2:
					$this->putUnsignedVarInt($rule[1]);
					break;
				case 3:
					$this->putLFloat($rule[1]);
					break;
			}
		}
	}

	/**
	 * Reads gamerules
	 * TODO: implement this properly
	 *
	 * @return array
	 */
	public function getGameRules() : array{
		$count = $this->getUnsignedVarInt();
		$rules = [];
		for($i = 0; $i < $count; ++$i){
			$name = $this->getString();
			$type = $this->getUnsignedVarInt();
			$value = null;
			switch($type){
				case 1:
					$value = $this->getBool();
					break;
				case 2:
					$value = $this->getUnsignedVarInt();
					break;
				case 3:
					$value = $this->getLFloat();
					break;
			}

			$rules[$name] = [$type, $value];
		}

		return $rules;
	}

	/**
	 * Writes a gamerule array
	 * TODO: implement this properly
	 *
	 * @param array $rules
	 */
	public function putGameRules(array $rules){
		$this->putUnsignedVarInt(count($rules));
		foreach($rules as $name => $rule){
			$this->putString($name);
			$this->putUnsignedVarInt($rule[0]);
			switch($rule[0]){
				case 1:
					$this->putBool($rule[1]);
					break;
				case 2:
					$this->putUnsignedVarInt($rule[1]);
					break;
				case 3:
					$this->putLFloat($rule[1]);
					break;
			}
		}
	}

	/**
	 * @return array
	 */
	protected function getEntityLink() : array{
		return [$this->getEntityUniqueId(), $this->getEntityUniqueId(), $this->getByte(), $this->getByte()];
	}

	/**
	 * @param array $link
	 */
	protected function putEntityLink(array $link){
		$this->putEntityUniqueId($link[0]);
		$this->putEntityUniqueId($link[1]);
		$this->putByte($link[2]);
		$this->putByte($link[3]);

	}

	/**
	 * @return PacketName|string
	 */
	public function getName(){
		return "DataPacket";
	}

}
