<?php

namespace pocketmine\item;

class EmptyMap extends Item {
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::EMPTY_MAP, $meta, $count, "Empty Map");
	}

}

