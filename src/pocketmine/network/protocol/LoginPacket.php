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

namespace pocketmine\network\protocol;

#include <rules/DataPacket.h>


class LoginPacket extends DataPacket{

	const NETWORK_ID = Info::LOGIN_PACKET;

	const MOJANG_PUBKEY = "MHYwEAYHKoZIzj0CAQYFK4EEACIDYgAE8ELkixyLcwlZryUQcu1TvPOmI2B7vX83ndnWRUaXm74wFfa5f/lwQNTfrLVHa2PmenpGI6JhIMUJaWZrjmMj90NoKNFSNBuKdm8rYiXsfaz3K36x/1U26HpG0ZxK/V1V";

	const EDITION_POCKET = 0;


	public $username;
	public $protocol;
	public $gameEdition;
	public $clientUUID;
	public $clientId;
	public $adRole;
	public $currentInputMode;
	public $defaultInputMode;
	public $deviceModel;
	public $deviceOS;
	public $gameVersion;
	public $guiScale;
	public $tenantId;
	public $uiProfile;
	public $identityPublicKey;
	public $serverAddress;

	public $skinId = null;
	public $skin = null;

	public function decode(){
		$this->protocol = $this->getInt();
		if(!in_array($this->protocol, Info::ACCEPTED_PROTOCOLS)){
			$this->buffer = null;

			return; //Do not attempt to decode for non-accepted protocols
		}

		$this->gameEdition = $this->getByte();

		$this->setBuffer($this->getString(), 0);

		$time = time();

		$chainData = json_decode($this->get($this->getLInt()))->{"chain"};
		// Start with the trusted one
		$chainKey = self::MOJANG_PUBKEY;
		while(!empty($chainData)){
			foreach($chainData as $index => $chain){
				list($verified, $webtoken) = $this->decodeToken($chain, $chainKey);
				if(isset($webtoken["extraData"])){
					if(isset($webtoken["extraData"]["displayName"])){
						$this->username = $webtoken["extraData"]["displayName"];
					}
					if(isset($webtoken["extraData"]["identity"])){
						$this->clientUUID = $webtoken["extraData"]["identity"];
					}
				}
				if($verified){
					$verified = isset($webtoken["nbf"]) && $webtoken["nbf"] <= $time && isset($webtoken["exp"]) && $webtoken["exp"] > $time;
				}
				if($verified and isset($webtoken["identityPublicKey"])){
					// Looped key chain. #blamemojang
					if($webtoken["identityPublicKey"] != self::MOJANG_PUBKEY) $chainKey = $webtoken["identityPublicKey"];
					break;
				}elseif($chainKey === null){
					// We have already gave up
					break;
				}
			}
			if(!$verified && $chainKey !== null){
				$chainKey = null;
			}else{
				unset($chainData[$index]);
			}
		}
		list($verified, $skinToken) = $this->decodeToken($this->get($this->getLInt()), $chainKey);
		if(isset($skinToken["AdRole"])){
			$this->AdRole = $skinToken["AdRole"];
		}
		if(isset($skinToken["ClientRandomId"])){
			$this->clientId = $skinToken["ClientRandomId"];
		}
		if(isset($skinToken["CurrentInputMode"])){
			$this->currentInputMode = $skinToken["CurrentInputMode"];
		}
		if(isset($skinToken["DefaultInputMode"])){
			$this->defaultInputMode = $skinToken["DefaultInputMode"];
		}
		if(isset($skinToken["DeviceModel"])){
			$this->deviceModel = $skinToken["DeviceModel"];
		}
		if(isset($skinToken["DeviceOS"])){
			$this->deviceOS = $skinToken["DeviceOS"];
		}
		if(isset($skinToken["GameVersion"])){
			$this->gameVersion = $skinToken["GameVersion"];
		}
		if(isset($skinToken["GuiScale"])){
			$this->guiScale = $skinToken["GuiScale"];
		}
		if(isset($skinToken["ServerAddress"])){
			$this->serverAddress = $skinToken["ServerAddress"];
		}
		if(isset($skinToken["SkinData"])){
			$this->skin = base64_decode($skinToken["SkinData"]);
		}
		if(isset($skinToken["SkinId"])){
			$this->skinId = $skinToken["SkinId"];
		}
		if(isset($skinToken["TenantId"])){
			$this->TenantId = $skinToken["TenantId"];
		}
		if(isset($skinToken["UIProfile"])){
			$this->UIProfile = $skinToken["UIProfile"];
		}
		if($verified){
			$this->identityPublicKey = $chainKey;
		}
	}

	public function encode(){

	}

	public function decodeToken($token, $key){
		$tokens = explode(".", $token);
		list($headB64, $payloadB64, $sigB64) = $tokens;

		if($key !== null and extension_loaded("openssl")){
			$sig = base64_decode(strtr($sigB64, '-_', '+/'), true);
			$rawLen = 48; // ES384
			for($i = $rawLen; $i > 0 and $sig[$rawLen - $i] == chr(0); $i--){
			}
			$j = $i + (ord($sig[$rawLen - $i]) >= 128 ? 1 : 0);
			for($k = $rawLen; $k > 0 and $sig[2 * $rawLen - $k] == chr(0); $k--){
			}
			$l = $k + (ord($sig[2 * $rawLen - $k]) >= 128 ? 1 : 0);
			$len = 2 + $j + 2 + $l;
			$derSig = chr(48);
			if($len > 255){
				throw new \RuntimeException("Invalid signature format");
			}elseif($len >= 128){
				$derSig .= chr(81);
			}
			$derSig .= chr($len) . chr(2) . chr($j);
			$derSig .= str_repeat(chr(0), $j - $i) . substr($sig, $rawLen - $i, $i);
			$derSig .= chr(2) . chr($l);
			$derSig .= str_repeat(chr(0), $l - $k) . substr($sig, 2 * $rawLen - $k, $k);

			$verified = openssl_verify($headB64 . "." . $payloadB64, $derSig, "-----BEGIN PUBLIC KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PUBLIC KEY-----\n", OPENSSL_ALGO_SHA384) === 1;
		}else{
			$verified = false;
		}

		return array($verified, json_decode(base64_decode($payloadB64), true));
	}

	/**
	 * @return PacketName|string
	 */
	public function getName(){
		return "LoginPacket";
	}

}
