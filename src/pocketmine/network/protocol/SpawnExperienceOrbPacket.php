<?php

namespace pocketmine\network\protocol;

#include <rules/DataPacket.h>


class SpawnExperienceOrbPacket extends DataPacket{

	const NETWORK_ID = Info::SPAWN_EXPERIENCE_ORB_PACKET;

	public $x;
	public $y;
	public $z;
	public $amount;

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putVector3f($this->x, $this->y, $this->z);
		$this->putVarInt($this->amount);
	}

	/**
	 * @return PacketName|string
	 */
	public function getName(){
		return "SpawnExperienceOrbPacket";
	}

}
