<?php

namespace pocketmine\network\protocol;

class TransferPacket extends DataPacket{

	const NETWORK_ID = Info::TRANSFER_PACKET;

	public $address;
	public $port = 19132; //default port

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putString($this->address);
		$this->putLShort($this->port);
	}
}
