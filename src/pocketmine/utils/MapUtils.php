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

namespace pocketmine\utils;

use pocketmine\block\Block;
use pocketmine\block\Planks;
use pocketmine\block\Prismarine;
use pocketmine\block\Slab;
use pocketmine\block\Slab2;
use pocketmine\block\Stone;
use pocketmine\item\Dye;
use pocketmine\item\Map;
use pocketmine\Server;

class MapUtils {

	public static $BaseMapColors = [];
	public static $MapColors = [];
	public static $idConfig;
	private static $cachedMaps = [];

	public function __construct() {
		$path = Server::getInstance()->getDataPath() . "maps";
		@mkdir($path);
		$filename = "idcounts.json";
		self::$idConfig = new Config($path.'/'.$filename, Config::JSON, ["map" => 0]);
		self::$BaseMapColors = [
			new Color(0, 0, 0, 0),
			new Color(127, 178, 56),
			new Color(247, 233, 163),
			new Color(167, 167, 167),
			new Color(255, 0, 0),
			new Color(160, 160, 255),
			new Color(167, 167, 167),
			new Color(0, 124, 0),
			new Color(255, 255, 255),
			new Color(164, 168, 184),
			new Color(183, 106, 47),
			new Color(112, 112, 112),
			new Color(64, 64, 255),
			new Color(104, 83, 50),
			//new 1.7 colors (13w42a/13w42b)
			new Color(255, 252, 245),
			new Color(216, 127, 51),
			new Color(178, 76, 216),
			new Color(102, 153, 216),
			new Color(229, 229, 51),
			new Color(127, 204, 25),
			new Color(242, 127, 165),
			new Color(76, 76, 76),
			new Color(153, 153, 153),
			new Color(76, 127, 153),
			new Color(127, 63, 178),
			new Color(51, 76, 178),
			new Color(102, 76, 51),
			new Color(102, 127, 51),
			new Color(153, 51, 51),
			new Color(25, 25, 25),
			new Color(250, 238, 77),
			new Color(92, 219, 213),
			new Color(74, 128, 255),
			new Color(0, 217, 58),
			new Color(21, 20, 31),
			new Color(112, 2, 0),
			//new 1.8 colors
			new Color(126, 84, 48)];

		for ($i = 0; $i < count(self::$BaseMapColors); ++$i) {
			/** @var Color $bc */
			$bc = self::$BaseMapColors[$i];
			self::$MapColors[$i * 4 + 0] = new Color((int)($bc->getR() * 180.0 / 255.0 + 0.5), (int)($bc->getG() * 180.0 / 255.0 + 0.5), (int)($bc->getB() * 180.0 / 255.0 + 0.5), $bc->getA());
			self::$MapColors[$i * 4 + 1] = new Color((int)($bc->getR() * 220.0 / 255.0 + 0.5), (int)($bc->getG() * 220.0 / 255.0 + 0.5), (int)($bc->getB() * 220.0 / 255.0 + 0.5), $bc->getA());
			self::$MapColors[$i * 4 + 2] = $bc;
			self::$MapColors[$i * 4 + 3] = new Color((int)($bc->getR() * 135.0 / 255.0 + 0.5), (int)($bc->getG() * 135.0 / 255.0 + 0.5), (int)($bc->getB() * 135.0 / 255.0 + 0.5), $bc->getA());
		}
	}

	public function getMapColors() {//TODO: make static
		return self::$MapColors;
	}


	public static function getNewId() {
		$id = self::$idConfig->get("map", 0);
		$id++;
		self::$idConfig->set("map", $id);
		self::$idConfig->save();
		return $id;
	}

	public static function cacheMap(Map $map){//TODO: serialize?
		self::$cachedMaps[$map->getMapId()] = $map;
	}

	public static function getCachedMap(int $uuid){
		return self::$cachedMaps[$uuid]??-1;
	}

	/**
	 * Returns the closest map color to a Color
	 * This will ignore alpha
	 * @param Color $color
	 * @return Color
	 */
	public function getClosestMapColor(Color $color) {
		if ($color->getA() > 128) return self::$MapColors[0];

		$index = 0;
		$best = -1;

		for ($i = 4; $i < count(self::$MapColors); $i++) {
			$distance = Color::getDistance($color, self::$MapColors[$i]);
			if ($distance < $best || $best == -1) {
				$best = $distance;
				$index = $i;
			}
		}

		return self::$MapColors[$index];
	}

	public static function distanceHSV(array $hsv1, array $hsv2) {
		return ($hsv1['v'] - $hsv2['v']) ** 2
			+ ($hsv1['s'] * cos($hsv1['h']) - $hsv2['s'] * cos($hsv2['h'])) ** 2
			+ ($hsv1['s'] * sin($hsv1['h']) - $hsv2['s'] * sin($hsv2['h'])) ** 2;
	}

	public static function exportToPDF(Map $map){
		if (!extension_loaded("gd")) {
			Server::getInstance()->getLogger()->error("Unable to find the gd extension, can't create PNG image from Map");
			var_dump(get_loaded_extensions());
			return false;
		}
		@mkdir(Server::getInstance()->getDataPath()."maps");
		$filename = Server::getInstance()->getDataPath()."maps/map_".$map->getMapId().".png";
		$colors = $map->getColors();
		$width = $map->getWidth();
		$height = $map->getHeight();
		$img = imagecreatetruecolor($width, $height);
		#imagecolortransparent($img, imagecolorallocate($img, 0, 0, 0));
		for ($y = 0; $y < $height; ++$y) {
			for ($x = 0; $x < $width; ++$x) {
				/** @var Color $color */
				$color = $colors[$y][$x];
				imagesetpixel($img, $x, $y, imagecolorallocate($img, $color->getR(),$color->getG(),$color->getB()));
			}
		}
		return imagepng($img, $filename);
	}

/*	public function exportToNBT(string $name) {TODO
byte[] data;
Tag.Compound t = new Tag.Compound(name,
new Tag.Compound("data", new Tag.Short("width", width),
new Tag.Short("height", height),
new Tag.Byte("scale", scale),
new Tag.Byte("dimension", (byte)dimension.getId()),
new Tag.Int("xCenter", xcenter),
new Tag.Int("zCenter", zcenter),
new Tag.ByteArray("colors", data = new byte[width*height])));
for(int i = 0; i < width; ++i)
{
for(int j = 0; j < height; ++j)
{
Color c = new Color(colors.getRGB(i, j));
for(int k = 0; k < MapColors.length; ++k)
{
if(c.equals(MapColors[k]))
{
data[i + j*width] = (byte)k;
break;
}
}
}
}
return t;
	}*/

	public static function getBlockColor(Block $block) {
		$meta = $block->getDamage();
		switch ($id = $block->getId()) {
			case Block::GRASS:
			case Block::SLIME_BLOCK:
				return new Color(127, 178, 56);
				break;
			case Block::SAND:
			case Block::SANDSTONE:
			case Block::SANDSTONE_STAIRS:
			case Block::STONE_SLAB && ($meta & 0x07) == Slab::SANDSTONE:
			case Block::DOUBLE_SLAB && $meta == Slab::SANDSTONE:
			case Block::GLOWSTONE_BLOCK:
			case Block::END_STONE:
			case Block::PLANKS && $meta == Planks::BIRCH:
			case Block::LOG && $meta == Planks::BIRCH:
			case Block::BIRCH_FENCE_GATE:
			case Block::FENCE && $meta = Planks::BIRCH:
			case Block::BIRCH_WOODEN_STAIRS:
			case Block::WOODEN_SLAB && ($meta & 0x07) == Planks::BIRCH:
				#case Block::BROWN_MUSHROOM_BLOCK://todo: meta check for non stem inside textures
				#case Block::BONE_BLOCK:
			case Block::END_BRICKS:
				return new Color(247, 233, 163);
				break;
			case Block::BED_BLOCK:
			case Block::COBWEB:
				#case Block::BROWN_MUSHROOM_BLOCK://todo: stem, sides only
				return new Color(199, 199, 199);
				break;
			case Block::LAVA:
			case Block::STILL_LAVA:
			case Block::TNT:
			case Block::FIRE:
			case Block::REDSTONE_BLOCK:
				return new Color(255, 0, 0);
				break;
			case Block::ICE:
			case Block::PACKED_ICE:
				#case Block::FROSTED_ICE:
				return new Color(160, 160, 255);
				break;
			case Block::IRON_BLOCK:
			case Block::IRON_DOOR_BLOCK:
			case Block::IRON_TRAPDOOR:
			case Block::IRON_BARS:
			case Block::BREWING_STAND_BLOCK:
			case Block::ANVIL:
			case Block::WEIGHTED_PRESSURE_PLATE_HEAVY:
				return new Color(167, 167, 167);
				break;
			case Block::SAPLING:
			case Block::LEAVES:
			case Block::LEAVES2:
			case Block::TALL_GRASS:
			case Block::DEAD_BUSH:
			case Block::RED_FLOWER:
			case Block::DOUBLE_PLANT:
			case Block::BROWN_MUSHROOM:
			case Block::RED_MUSHROOM:
			case Block::WHEAT_BLOCK:
			case Block::CARROT_BLOCK:
			case Block::POTATO_BLOCK:
			case Block::BEETROOT_BLOCK:
			case Block::CACTUS:
			case Block::SUGARCANE_BLOCK:
			case Block::PUMPKIN_STEM:
			case Block::MELON_STEM:
			case Block::VINE:
			case Block::LILY_PAD:
				return new Color(0, 124, 0);
				break;
			case Block::WOOL && $meta == Dye::WHITE:
			case Block::CARPET && $meta == Dye::WHITE:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::WHITE:
			case Block::SNOW_LAYER:
			case Block::SNOW_BLOCK:
				return new Color(255, 255, 255);
				break;
			case Block::CLAY_BLOCK:
			case Block::MONSTER_EGG_BLOCK:
				return new Color(164, 168, 184);
				break;
			case Block::DIRT:
			case Block::FARMLAND:
			case Block::STONE && $meta == Stone::GRANITE:
			case Block::STONE && $meta == Stone::POLISHED_GRANITE:
			case Block::SAND && $meta == 1:
			case Block::RED_SANDSTONE:
			case Block::RED_SANDSTONE_STAIRS:
			case Block::SLAB2 && ($meta & 0x07) == Slab2::RED_SANDSTONE:
			case Block::LOG && $meta == Planks::JUNGLE:
			case Block::PLANK && $meta == Planks::JUNGLE:
			case Block::JUNGLE_FENCE_GATE:
			case Block::FENCE && $meta == Planks::JUNGLE:
			case Block::JUNGLE_WOOD_STAIRS:
			case Block::WOODEN_SLAB && ($meta & 0x07) == Planks::JUNGLE:
				return new Color(151, 109, 77);
				break;
			case Block::STONE:
			case Block::STONE_SLAB && ($meta & 0x07) == Slab::STONE:
			case Block::COBBLESTONE:
			case Block::COBBLE_STAIRS:
			case Block::STONE_SLAB && ($meta & 0x07) == Slab::COBBLESTONE:
			case Block::COBBLESTONE_WALL:
			case Block::MOSSY_STONE:
			case Block::STONE && $meta == Stone::ANDESITE:
			case Block::STONE && $meta == Stone::POLISHED_ANDESITE:
			case Block::BEDROCK:
			case Block::GOLD_ORE:
			case Block::IRON_ORE:
			case Block::COAL_ORE:
			case Block::LAPIS_ORE:
			case Block::DISPENSER:
			case Block::DROPPER:
			case Block::STICKY_PISTON:
			case Block::PISTON:
			case Block::PISTON_HEAD:
			case Block::BLOCK_MOVED_BY_PISTON:
			case Block::MONSTER_SPAWNER:
			case Block::DIAMOND_ORE:
			case Block::FURNACE:
			case Block::STONE_PRESSURE_PLATE:
			case Block::REDSTONE_ORE:
			case Block::STONE_BRICK:
			case Block::STONE_BRICK_STAIRS:
			case Block::STONE_SLAB && ($meta & 0x07) == Slab::STONE_BRICK:
			case Block::ENDER_CHEST:
			case Block::HOPPER_BLOCK:
			case Block::GRAVEL:
			case Block::OBSERVER:
				return new Color(112, 112, 112);
				break;
			case Block::WATER:
			case Block::STILL_WATER:
				return new Color(64, 64, 255);
				break;
			case Block::WOOD && $meta == Planks::OAK:
			case Block::PLANK && $meta == Planks::OAK:
			case Block::FENCE && $meta == Planks::OAK:
			case Block::OAK_FENCE_GATE:
			case Block::OAK_WOOD_STAIRS:
			case Block::WOODEN_SLAB && ($meta & 0x07) == Planks::OAK:
			case Block::NOTEBLOCK:
			case Block::BOOKSHELF:
			case Block::CHEST:
			case Block::TRAPPED_CHEST:
			case Block::CRAFTING_TABLE:
			case Block::WOODEN_DOOR_BLOCK:
			case Block::BIRCH_DOOR_BLOCK:
			case Block::SPRUCE_DOOR_BLOCK:
			case Block::JUNGLE_DOOR_BLOCK:
			case Block::ACACIA_DOOR_BLOCK:
			case Block::DARK_OAK_DOOR_BLOCK:
			case Block::SIGN_POST:
			case Block::WALL_SIGN:
			case Block::WOODEN_PRESSURE_PLATE:
				#case Block::JUKEBOX:
			case Block::WOODEN_TRAPDOOR:
			case Block::BROWN_MUSHROOM_BLOCK:
				#case Block::BANNER:
			case Block::DAYLIGHT_SENSOR:
			case Block::DAYLIGHT_SENSOR_INVERTED:
				return new Color(143, 119, 72);
				break;
			case Block::QUARTZ_BLOCK:
			case Block::STONE_SLAB && ($meta & 0x07) == Slab::QUARTZ:
			case Block::QUARTZ_STAIRS:
			case Block::STONE && $meta == Stone::DIORITE:
			case Block::STONE && $meta == Stone::POLISHED_DIORITE:
			case Block::SEA_LANTERN:
				return new Color(255, 252, 245);
				break;
			case Block::WOOL && $meta == Dye::ORANGE:
			case Block::CARPET && $meta == Dye::ORANGE:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::ORANGE:
			case Block::PUMPKIN:
			case Block::JACK_O_LANTERN:
			case Block::HARDENED_CLAY:
			case Block::WOOD && $meta == Planks::ACACIA:
			case Block::PLANK && $meta == Planks::ACACIA:
			case Block::FENCE && $meta == Planks::ACACIA:
			case Block::ACACIA_FENCE_GATE:
			case Block::ACACIA_WOOD_STAIRS:
			case Block::WOODEN_SLAB && ($meta & 0x07) == Planks::ACACIA:
				return new Color(216, 127, 51);
				break;
			case Block::WOOL && $meta == Dye::MAGENTA:
			case Block::CARPET && $meta == Dye::MAGENTA:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::MAGENTA:
			case Block::PURPUR:
			case Block::PURPUR_STAIRS:
			case Block::SLAB2 && ($meta & 0x07) == Slab2::PURPUR:
				return new Color(178, 76, 216);
				break;
			case Block::WOOL && $meta == Dye::LIGHT_BLUE:
			case Block::CARPET && $meta == Dye::LIGHT_BLUE:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::LIGHT_BLUE:
				return new Color(102, 153, 216);
				break;
			case Block::WOOL && $meta == Dye::YELLOW:
			case Block::CARPET && $meta == Dye::YELLOW:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::YELLOW:
			case Block::HAY_BALE:
			case Block::SPONGE:
				return new Color(229, 229, 51);
				break;
			case Block::WOOL && $meta == Dye::LIME:
			case Block::CARPET && $meta == Dye::LIME:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::LIME:
			case Block::MELON_BLOCK:
				return new Color(229, 229, 51);
				break;
			case Block::WOOL && $meta == Dye::PINK:
			case Block::CARPET && $meta == Dye::PINK:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::PINK:
				return new Color(242, 127, 165);
				break;
			case Block::WOOL && $meta == Dye::GRAY:
			case Block::CARPET && $meta == Dye::GRAY:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::GRAY:
			case Block::CAULDRON_BLOCK:
				return new Color(76, 76, 76);
				break;
			case Block::WOOL && $meta == Dye::LIGHT_GRAY:
			case Block::CARPET && $meta == Dye::LIGHT_GRAY:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::LIGHT_GRAY:
				#case Block::STRUCTURE_BLOCK:
				return new Color(153, 153, 153);
				break;
			case Block::WOOL && $meta == Dye::CYAN:
			case Block::CARPET && $meta == Dye::CYAN:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::CYAN:
			case Block::PRISMARINE && $meta == Prismarine::NORMAL:
				return new Color(76, 127, 153);
				break;
			case Block::WOOL && $meta == Dye::PURPLE:
			case Block::CARPET && $meta == Dye::PURPLE:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::PURPLE:
			case Block::MYCELIUM:
				#case Block::COMMAND_BLOCK://meta = repeating
			case Block::CHORUS_PLANT:
			case Block::CHORUS_FLOWER:
				return new Color(127, 63, 178);
				break;
			case Block::WOOL && $meta == Dye::DARK_BLUE:
			case Block::CARPET && $meta == Dye::DARK_BLUE:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::DARK_BLUE:
				return new Color(51, 76, 178);
				break;
			case Block::WOOL && $meta == Dye::BROWN:
			case Block::CARPET && $meta == Dye::BROWN:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::BROWN:
			case Block::SOUL_SAND:
			case Block::WOOD && $meta == Planks::DARK_OAK:
			case Block::PLANK && $meta == Planks::DARK_OAK:
			case Block::FENCE && $meta == Planks::DARK_OAK:
			case Block::DARK_OAK_FENCE_GATE:
			case Block::DARK_OAK_WOOD_STAIRS:
			case Block::WOODEN_SLAB && ($meta & 0x07) == Planks::DARK_OAK:
				#case Block::COMMAND_BLOCK://meta = impulse
				return new Color(102, 76, 51);
				break;
			case Block::WOOL && $meta == Dye::GREEN:
			case Block::CARPET && $meta == Dye::GREEN:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::GREEN:
			case Block::END_PORTAL_FRAME:
				#case Block::COMMAND_BLOCK://meta = chain
				return new Color(102, 127, 51);
				break;
			case Block::WOOL && $meta == Dye::RED:
			case Block::CARPET && $meta == Dye::RED:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::RED:
			case Block::RED_MUSHROOM_BLOCK://todo: meta
			case Block::BRICKS:
			case Block::STONE_SLAB && ($meta & 0x07) == Slab::BRICK:
			case Block::BRICK_STAIRS:
			case Block::ENCHANTING_TABLE:
			case Block::NETHER_WART_BLOCK:
				#case Block::NETHER_WART_BLOCK://For the future: a block with this name will be introduced soon!
				return new Color(153, 51, 51);
				break;
			case Block::WOOL && $meta == Dye::BLACK:
			case Block::CARPET && $meta == Dye::BLACK:
			case Block::STAINED_HARDENED_CLAY && $meta == Dye::BLACK:
			case Block::DRAGON_EGG:
			case Block::COAL_BLOCK:
			case Block::OBSIDIAN:
			case Block::END_PORTAL_BLOCK:
				return new Color(25, 25, 25);
				break;
			case Block::GOLD_BLOCK:
			case Block::LIGHT_WEIGHTED_PRESSURE_PLATE:
				return new Color(250, 238, 77);
				break;
			case Block::DIAMOND_BLOCK:
			case Block::PRISMARINE && $meta == Prismarine::DARK:
			case Block::PRISMARINE && $meta == Prismarine::BRICKS:
			case Block::BEACON:
				return new Color(92, 219, 213);
				break;
			case Block::LAPIS_BLOCK:
				return new Color(74, 128, 255);
				break;
			case Block::EMERALD_BLOCK:
				return new Color(0, 217, 58);
				break;
			case Block::PODZOL:
			case Block::WOOD && $meta == Planks::SPRUCE:
			case Block::PLANK && $meta == Planks::SPRUCE:
			case Block::FENCE && $meta == Planks::SPRUCE:
			case Block::SPRUCE_FENCE_GATE:
			case Block::SPRUCE_WOOD_STAIRS:
			case Block::WOODEN_SLAB && ($meta & 0x07) == Planks::SPRUCE:
				return new Color(129, 86, 49);
				break;
			case Block::NETHERRACK:
			case Block::NETHER_QUARTZ_ORE:
			case Block::NETHER_BRICK_FENCE:
			case Block::NETHER_BRICK_BLOCK:
				#case Block::RED_NETHER_BRICK_BLOCK:
				#case Block::MAGMA:
			case Block::NETHER_BRICK_STAIRS:
			case Block::STONE_SLAB && ($meta & 0x07) == Slab::NETHER_BRICK:
				return new Color(112, 2, 0);
				break;
			default:
				return new Color(0, 0, 0, 0);
		}
	}
}