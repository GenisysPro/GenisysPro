<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 *  _____            _               _____           
 * / ____|          (_)             |  __ \          
 *| |  __  ___ _ __  _ ___ _   _ ___| |__) | __ ___  
 *| | |_ |/ _ \ '_ \| / __| | | / __|  ___/ '__/ _ \ 
 *| |__| |  __/ | | | \__ \ |_| \__ \ |   | | | (_) |
 * \_____|\___|_| |_|_|___/\__, |___/_|   |_|  \___/ 
 *                         __/ |                    
 *                        |___/                     
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

namespace pocketmine\resourcepacks;

class ResourcePackInfoEntry {
	protected $packId; //UUID
	protected $version;
	protected $packSize;

	/**
	 * ResourcePackInfoEntry constructor.
	 *
	 * @param string $packId
	 * @param string $version
	 * @param int    $packSize
	 */
	public function __construct(string $packId, string $version, $packSize = 0){
		$this->packId = $packId;
		$this->version = $version;
		$this->packSize = $packSize;
	}

	/**
	 * @return string
	 */
	public function getPackId() : string{
		return $this->packId;
	}

	/**
	 * @return string
	 */
	public function getVersion() : string{
		return $this->version;
	}

	/**
	 * @return int
	 */
	public function getPackSize(){
		return $this->packSize;
	}

}