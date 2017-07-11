<?php

namespace pocketmine\level\generator\normal\biome;

use pocketmine\level\generator\populator\Cactus;
use pocketmine\level\generator\populator\DeadBush;

class BeachBiome extends SandyBiome {

	/**
	 * BeachBiome constructor.
	 */
	public function __construct(){
		parent::__construct();

		$this->removePopulator(Cactus::class);
		$this->removePopulator(DeadBush::class);

		$this->setElevation(62, 65);
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Beach";
	}
} 