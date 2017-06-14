<?php

namespace pocketmine\network\protocol;

class UpdateTradePacket extends DataPacket{

	const NETWORK_ID = Info::UPDATE_TRADE_PACKET;

	public $byte1;
	public $byte2;
	public $varint1;
	public $varint2;
	public $isWilling;
	public $traderEid;
	public $playerEid;
	public $displayName;
	public $offers;

	public function decode(){
		$this->byte1 = $this->getByte();
		$this->byte2 = $this->getByte();
		$this->varint1 = $this->getVarInt();
		$this->varint2 = $this->getVarInt();
		$this->isWilling = $this->getBool();
		$this->traderEid = $this->getEntityId();
		$this->playerEid = $this->getEntityId();
		$this->displayName = $this->getString();
		$this->offers = $this->get(true);
	}

	public function encode(){
		$this->reset();
		$this->putByte($this->byte1);
		$this->putByte($this->byte2);
		$this->putVarInt($this->varint1);
		$this->putVarInt($this->varint2);
		$this->putBool($this->isWilling);
		$this->putEntityId($this->traderEid);
		$this->putEntityId($this->playerEid);
		$this->putString($this->displayName);
		$this->put($this->offers);
	}
}