<?php

/*
 *
 *    _______                                _
 *   |__   __|                              | |
 *      | | ___  ___ ___  ___ _ __ __ _  ___| |_
 *      | |/ _ \/ __/ __|/ _ \  __/ _` |/ __| __|
 *      | |  __/\__ \__ \  __/ | | (_| | (__| |_
 *      |_|\___||___/___/\___|_|  \__,_|\___|\__|
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Tessetact Team
 * @link http://www.github.com/TesseractTeam/Tesseract
 * 
 *
 */

#include <rules/DataPacket.h>
namespace pocketmine\network\protocol;


class CameraPacket extends DataPacket{

	const NETWORK_ID = Info::CAMERA_PACKET;
	public $eid;

	public function decode(){
	}

	public function encode(){
		$this->reset();
		$this->putVarInt($this->eid);
		$this->putVarInt($this->eid);
	}

	/**
	 * @return PacketName|string
	 */
	public function getName(){
		return "BossEventPacket";
	}

}