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

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class SetTitlePacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SET_TITLE_PACKET;

	const TYPE_CLEAR = 0;
	const TYPE_RESET = 1;
	const TYPE_TITLE = 2;
	const TYPE_SUB_TITLE = 3;
	const TYPE_ACTION_BAR = 4;
	const TYPE_TIMES = 5;

	public $type;
	public $title;
	public $fadeInDuration;
	public $duration;
	public $fadeOutDuration;

	/**
	 *
	 */
	public function decode(){
		$this->type = $this->getVarInt();
		$this->title = $this->getString();
		$this->fadeInDuration = $this->getVarInt();
		$this->duration = $this->getVarInt();
		$this->fadeOutDuration = $this->getVarInt();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putVarInt($this->type);
		$this->putString($this->title);
		$this->putVarInt($this->fadeInDuration);
		$this->putVarInt($this->duration);
		$this->putVarInt($this->fadeOutDuration);
	}

	/**
	 * @return PacketName|string
	 */
	public function getName(){
		return "SetTitlePacket";
	}

}
