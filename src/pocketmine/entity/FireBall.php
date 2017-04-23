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

/* 为凋零而准备! */
namespace pocketmine\entity;

use pocketmine\level\format\Chunk as FullChunk;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\level\Explosion;

class FireBall extends Projectile {
    const NETWORK_ID = 85;

    public $width = 0.5;
    public $height = 0.5;

    protected $damage = 4;

    protected $drag = 0.01;
    protected $gravity = 0.05;

    protected $isCritical;
    protected $canExplode = false;

    public function __construct(FullChunk $chunk, CompoundTag $nbt, Entity $shootingEntity = null, bool $critical = false) {
        parent::__construct($chunk, $nbt, $shootingEntity);

        $this->isCritical = $critical;
    }

    public function isExplode(): bool {
        return $this->canExplode;
    }

    public function setExplode(bool $bool) {
        $this->canExplode = $bool;
    }

    public function onUpdate($currentTick) {
        if ($this->closed) {
            return false;
        }

        $this->timings->startTiming();

        $hasUpdate = parent::onUpdate($currentTick);

        if (!$this->hadCollision and $this->isCritical) {
            $this->level->addParticle(new CriticalParticle($this->add(
                $this->width / 2 + mt_rand(-100, 100) / 500,
                $this->height / 2 + mt_rand(-100, 100) / 500,
                $this->width / 2 + mt_rand(-100, 100) / 500)));
        } elseif ($this->onGround) {
            $this->isCritical = false;
        }

        if ($this->age > 1200 or $this->isCollided) {
            if ($this->isCollided and $this->canExplode) {
                    $explosion = new Explosion($this, $ev->getForce(), $this->shootingEntity);
                    if ($ev->isBlockBreaking()) {
                        $explosion->explodeA();
                    }
                    $explosion->explodeB();
            }
            $this->kill();
            $hasUpdate = true;
        }

        $this->timings->stopTiming();
        return $hasUpdate;
    }

    public function spawnTo(Player $player) {
        $pk = new AddEntityPacket();
        $pk->type = self::NETWORK_ID;
        $pk->eid = $this->getId();
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->speedX = $this->motionX;
        $pk->speedY = $this->motionY;
        $pk->speedZ = $this->motionZ;
        $pk->metadata = $this->dataProperties;
        $player->dataPacket($pk);

        parent::spawnTo($player);
    }

}
