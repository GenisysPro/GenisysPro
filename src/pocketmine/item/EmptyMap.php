<?php

namespace pocketmine\item;

class EmptyMap extends Item {
	/**
	 * EmptyMap constructor.
	 *
	 * @param int $meta
	 * @param int $count
	 */
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::EMPTY_MAP, $meta, $count, "Empty Map");
	}

}

