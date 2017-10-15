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
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author GenisysPro
 * @link https://github.com/GenisysPro/GenisysPro
 *
 *
*/

namespace pocketmine\entity;

use pocketmine\block\Wool;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item as ItemItem;
use pocketmine\level\Level;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;

class Sheep extends Animal implements Colorable {

    const NETWORK_ID = 13;

    const DATA_COLOR_INFO = 16;

    public $width = 0.0;
    public $length = 1.3;
    public $height = 0.9;

    /**
     * @return string
     */
    public function getName(): string{
        return "Sheep";
    }

    /**
     * Sheep constructor.
     *
     * @param Level $level
     * @param CompoundTag $nbt
     */
    public function __construct(Level $level, CompoundTag $nbt){
        if (!isset($nbt->Color)) {
            $nbt->Color = new ByteTag("Color", self::getRandomColor());
        }
        parent::__construct($level, $nbt);

        $this->setDataProperty(self::DATA_COLOR_INFO, self::DATA_TYPE_BYTE, $this->getColor());
    }

    /**
     * @return int
     */
    public static function getRandomColor(): int{
        $rand = "";
        $rand .= str_repeat(Wool::WHITE . " ", 20);
        $rand .= str_repeat(Wool::ORANGE . " ", 5);
        $rand .= str_repeat(Wool::MAGENTA . " ", 5);
        $rand .= str_repeat(Wool::LIGHT_BLUE . " ", 5);
        $rand .= str_repeat(Wool::YELLOW . " ", 5);
        $rand .= str_repeat(Wool::GRAY . " ", 10);
        $rand .= str_repeat(Wool::LIGHT_GRAY . " ", 10);
        $rand .= str_repeat(Wool::CYAN . " ", 5);
        $rand .= str_repeat(Wool::PURPLE . " ", 5);
        $rand .= str_repeat(Wool::BLUE . " ", 5);
        $rand .= str_repeat(Wool::BROWN . " ", 5);
        $rand .= str_repeat(Wool::GREEN . " ", 5);
        $rand .= str_repeat(Wool::RED . " ", 5);
        $rand .= str_repeat(Wool::BLACK . " ", 10);
        $arr = explode(" ", $rand);
        return intval($arr[mt_rand(0, count($arr) - 1)]);
    }

    /**
     * @return int
     */
    public function getColor(): int{
        return (int)$this->namedtag["Color"];
    }

    /**
     * @param int $color
     */
    public function setColor(int $color){
        $this->namedtag->Color = new ByteTag("Color", $color);
    }

    /**
     * @param Player $player
     */
    public function spawnTo(Player $player){
        $pk = new AddEntityPacket();
        $pk->eid = $this->getId();
        $pk->type = Sheep::NETWORK_ID;
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->speedX = $this->motionX;
        $pk->speedY = $this->motionY;
        $pk->speedZ = $this->motionZ;
        $pk->yaw = $this->yaw;
        $pk->pitch = $this->pitch;
        $pk->metadata = $this->dataProperties;
        $player->dataPacket($pk);

        parent::spawnTo($player);
    }

    /**
     * @return array
     */
	public function getDrops(){
		$cause = $this->lastDamageCause;
		if($cause instanceof EntityDamageByEntityEvent){
			$damager = $cause->getDamager();
			if($damager instanceof Player){
				$lootingL = $damager->getItemInHand()->getEnchantmentLevel(Enchantment::TYPE_WEAPON_LOOTING);
				$drops = [ItemItem::get(ItemItem::WOOL, $this->getColor(), 1)];
                $drops[] = ItemItem::get(ItemItem::RAW_MUTTON, 0, mt_rand(1, 2 + $lootingL));
				return $drops;
			}
		}
		return [];
	}
}
