<?php

/*
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
 * @author GenisysPro
 * @link https://github.com/GenisysPro/GenisysPro
 *
 *
*/
 
 namespace pocketmine\command\defaults;
 
use pocketmine\network\protocol\SetTitlePacket;
use pocketmine\command\CommandSender;

class TitleCommand extends VanillaCommand {

	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.title.description",
			"%pocketmine.command.title.usage"
		);
		$this->setPermission("pocketmine.command.title");
	}
  
	public function execute(CommandSender $sender, $currentAlias, array $args){
		if($sender instanceof Player){
			if(!$this->testPermission($sender)){
				return true;
			}
			if(count($args) <= 0){
				$sender->sendMessage("Usage: /title <title> <subtile> [text]");
				return false;
			}
        }
    }
}
