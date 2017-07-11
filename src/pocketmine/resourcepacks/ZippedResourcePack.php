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

/*
 *Zip材质包加载接口
 *
*/

namespace pocketmine\resourcepacks;


class ZippedResourcePack implements ResourcePack {

	/**
	 * @param \stdClass $manifest
	 *
	 * @return bool
	 */
	public static function verifyManifest(\stdClass $manifest){
		if(!isset($manifest->format_version) or !isset($manifest->header) or !isset($manifest->modules)){
			return false;
		}
		return
			isset($manifest->header->description) and
			isset($manifest->header->name) and
			isset($manifest->header->uuid) and
			isset($manifest->header->version) and
			count($manifest->header->version) === 3;
	}

	/** @var string */
	protected $path;

	/** @var \stdClass */
	protected $manifest;

	/** @var string */
	protected $sha256 = null;

	/** @var resource */
	protected $fileResource;

	/**
	 * ZippedResourcePack constructor.
	 *
	 * @param string $zipPath
	 */
	public function __construct(string $zipPath){
		$this->path = $zipPath;

		if(!file_exists($zipPath)){
			throw new \InvalidArgumentException("无法打开材质包 $zipPath: 文件夹无法打开");
		}

		$archive = new \ZipArchive();
		if(($openResult = $archive->open($zipPath)) !== true){
			throw new \InvalidStateException("打开 $zipPath $openResult");//Yeah, I don't speak, that...
		}

		if(($manifestData = $archive->getFromName("manifest.json")) === false){
			if(($manifestData = $archive->getFromName("pack_manifest.json")) === false){
				throw new \InvalidStateException("无法加载材质包 $zipPath: 找不到主类");
			}
		}

		$archive->close();

		$manifest = json_decode($manifestData);
		if(!self::verifyManifest($manifest)){
			throw new \InvalidStateException("无法加载材质包 $zipPath: 主类错误或不完整");
		}

		$this->manifest = $manifest;

		$this->fileResource = fopen($zipPath, "rb");
	}

	/**
	 * @return string
	 */
	public function getPackName() : string{
		return $this->manifest->header->name;
	}

	/**
	 * @return string
	 */
	public function getPackVersion() : string{
		return implode(".", $this->manifest->header->version);
	}

	/**
	 * @return string
	 */
	public function getPackId() : string{
		return $this->manifest->header->uuid;
	}

	/**
	 * @return int
	 */
	public function getPackSize() : int{
		return filesize($this->path);
	}

	/**
	 * @param bool $cached
	 *
	 * @return string
	 */
	public function getSha256(bool $cached = true) : string{
		if($this->sha256 === null or !$cached){
			$this->sha256 = hash_file("sha256", $this->path, true);
		}
		return $this->sha256;
	}

	/**
	 * @param int $start
	 * @param int $length
	 *
	 * @return string
	 */
	public function getPackChunk(int $start, int $length) : string{
		fseek($this->fileResource, $start);
		if(feof($this->fileResource)){
			throw new \RuntimeException("Requested a resource pack chunk with invalid start offset");
		}
		return fread($this->fileResource, $length);
	}
}