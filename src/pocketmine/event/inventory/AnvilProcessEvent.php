<?php
namespace pocketmine\event\inventory;

use pocketmine\event\Cancellable;
use pocketmine\inventory\AnvilInventory;
class AnvilProcessEvent extends InventoryEvent implements Cancellable{

	public static $handlerList = null;
	protected $inventory;
  
	public function __construct(AnvilInventory $inventory){
		parent::__construct($inventory);
    	$this->inventory = $inventory;
	}

   /**
	 * @return EventName|string
     */
	public function getName(){
		return "AnvilProcessEvent";
	}
}
