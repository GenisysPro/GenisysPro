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

use pocketmine\network\mcpe\protocol\types\PlayerPermissions;

class StartGamePacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::START_GAME_PACKET;

	public $entityUniqueId;
	public $entityRuntimeId;
	public $playerGamemode;

	public $x;
	public $y;
	public $z;

	public $pitch;
	public $yaw;

	public $seed;
	public $dimension;
	public $generator = 1; //default infinite - 0 old, 1 infinite, 2 flat
	public $worldGamemode;
	public $difficulty;
	public $spawnX;
	public $spawnY;
	public $spawnZ;
	public $hasAchievementsDisabled = true;
	public $time = -1;
	public $eduMode = false;
	public $rainLevel;
	public $lightningLevel;
	public $isMultiplayerGame = true;
	public $hasLANBroadcast = true;
	public $hasXboxLiveBroadcast = false;
	public $commandsEnabled;
	public $isTexturePacksRequired = true;
	public $gameRules = [];
	public $hasBonusChestEnabled = false;
	public $hasTrustPlayersEnabled = false;
	public $defaultPlayerPermission = PlayerPermissions::MEMBER; //TODO
	public $xboxLiveBroadcastMode = 0; //TODO: find values

	public $levelId = "";
	public $worldName;
	public $premiumWorldTemplateId = "";
	public $unknownBool = false;
	public $currentTick = 0;

	public $enchantmentSeed = 0;

	/**
	 *
	 */
	public function decode(){

	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putEntityId($this->entityUniqueId); //EntityUniqueID
		$this->putEntityId($this->entityRuntimeId); //EntityRuntimeID
		$this->putVarInt($this->playerGamemode); //client gamemode, other field is world gamemode
		$this->putVector3f($this->x, $this->y, $this->z);
		$this->putLFloat($this->pitch);
		$this->putLFloat($this->yaw);
		$this->putVarInt($this->seed);
		$this->putVarInt($this->dimension);
		$this->putVarInt($this->generator);
		$this->putVarInt($this->worldGamemode);
		$this->putVarInt($this->difficulty);
		$this->putBlockCoords($this->spawnX, $this->spawnY, $this->spawnZ);
		$this->putBool($this->hasAchievementsDisabled);
		$this->putVarInt($this->time);
		$this->putBool($this->eduMode);
		$this->putLFloat($this->rainLevel);
		$this->putLFloat($this->lightningLevel);
        $this->putBool($this->isMultiplayerGame);
        $this->putBool($this->hasLANBroadcast);
        $this->putBool($this->hasXboxLiveBroadcast);
		$this->putBool($this->commandsEnabled);
		$this->putBool($this->isTexturePacksRequired);
		$this->putGameRules($this->gameRules);
		$this->putBool($this->hasBonusChestEnabled);
		// $this->putBool($this->hasStartWithMapEnabled);
		$this->putBool($this->hasTrustPlayersEnabled);
		$this->putVarInt($this->defaultPlayerPermission);
		$this->putVarInt($this->xboxLiveBroadcastMode);
		$this->putString($this->levelId);
		$this->putString($this->worldName);
		$this->putString($this->premiumWorldTemplateId);
		$this->putBool($this->unknownBool);
		$this->putLLong($this->currentTick);

		$this->putVarInt($this->enchantmentSeed);
	}

}
