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

namespace pocketmine\math;

use pocketmine\utils\Random;

class Vector2 {
	public $x;
	public $y;

	/**
	 * Vector2 constructor.
	 *
	 * @param int $x
	 * @param int $y
	 */
	public function __construct($x = 0, $y = 0){
		$this->x = $x;
		$this->y = $y;
	}

	/**
	 * @return int
	 */
	public function getX(){
		return $this->x;
	}

	/**
	 * @return int
	 */
	public function getY(){
		return $this->y;
	}

	/**
	 * @return int
	 */
	public function getFloorX(){
		return (int) $this->x;
	}

	/**
	 * @return int
	 */
	public function getFloorY(){
		return (int) $this->y;
	}

	/**
	 * @param     $x
	 * @param int $y
	 *
	 * @return Vector2
	 */
	public function add($x, $y = 0){
		if($x instanceof Vector2){
			return $this->add($x->x, $x->y);
		}else{
			return new Vector2($this->x + $x, $this->y + $y);
		}
	}

	/**
	 * @param     $x
	 * @param int $y
	 *
	 * @return Vector2
	 */
	public function subtract($x, $y = 0){
		if($x instanceof Vector2){
			return $this->add(-$x->x, -$x->y);
		}else{
			return $this->add(-$x, -$y);
		}
	}

	/**
	 * @return Vector2
	 */
	public function ceil(){
		return new Vector2((int) ($this->x + 1), (int) ($this->y + 1));
	}

	/**
	 * @return Vector2
	 */
	public function floor(){
		return new Vector2((int) $this->x, (int) $this->y);
	}

	/**
	 * @return Vector2
	 */
	public function round(){
		return new Vector2(round($this->x), round($this->y));
	}

	/**
	 * @return Vector2
	 */
	public function abs(){
		return new Vector2(abs($this->x), abs($this->y));
	}

	/**
	 * @param $number
	 *
	 * @return Vector2
	 */
	public function multiply($number){
		return new Vector2($this->x * $number, $this->y * $number);
	}

	/**
	 * @param $number
	 *
	 * @return Vector2
	 */
	public function divide($number){
		return new Vector2($this->x / $number, $this->y / $number);
	}

	/**
	 * @param     $x
	 * @param int $y
	 *
	 * @return float
	 */
	public function distance($x, $y = 0){
		if($x instanceof Vector2){
			return sqrt($this->distanceSquared($x->x, $x->y));
		}else{
			return sqrt($this->distanceSquared($x, $y));
		}
	}

	/**
	 * @param     $x
	 * @param int $y
	 *
	 * @return number
	 */
	public function distanceSquared($x, $y = 0){
		if($x instanceof Vector2){
			return $this->distanceSquared($x->x, $x->y);
		}else{
			return pow($this->x - $x, 2) + pow($this->y - $y, 2);
		}
	}

	/**
	 * @return float
	 */
	public function length(){
		return sqrt($this->lengthSquared());
	}

	/**
	 * @return int
	 */
	public function lengthSquared(){
		return $this->x * $this->x + $this->y * $this->y;
	}

	/**
	 * @return Vector2
	 */
	public function normalize(){
		$len = $this->lengthSquared();
		if($len != 0){
			return $this->divide(sqrt($len));
		}

		return new Vector2(0, 0);
	}

	/**
	 * @param Vector2 $v
	 *
	 * @return int
	 */
	public function dot(Vector2 $v){
		return $this->x * $v->x + $this->y * $v->y;
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return "Vector2(x=" . $this->x . ",y=" . $this->y . ")";
	}

	/**
	 * @param Random $random
	 *
	 * @return Vector2
	 */
	public static function createRandomDirection(Random $random){
		return VectorMath::getDirection2D($random->nextFloat() * 2 * pi());
	}
}