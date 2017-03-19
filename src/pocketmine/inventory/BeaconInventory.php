<?php

namespace pocketmine\inventory;

use pocketmine\tile\Beacon;

class BeaconInventory extends ContainerInventory {
	
	public function __construct(Beacon $tile){
		parent::__construct($tile, InventoryType::get(InventoryType::BEACON));
	}
	
	public function getHolder() {
		return $this->holder;
	}
}