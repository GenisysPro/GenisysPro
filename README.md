<!-- Notice to GenisysPro collaborators: please do not push any commit to this README when finally merging this Beta branch into `master`, thank you! -->

# Branch for Minecraft PE/Windows 10 v1.2 Beta Testing Versions

|  Travis CI |
| :---: |
| [![Travis CI](https://api.travis-ci.org/GenisysPro/GenisysPro.svg?branch=mcpe-1.2-beta)](https://travis-ci.org/GenisysPro/GenisysPro) |

This is the branch for Minecraft PE/Windows v1.2 Beta.   
**We DO NOT suggest any production use of this branch!** Both Minecraft Beta clients and this branch might be unstable.  
As Mojang is still developing v1.2 and may introduce any big change to the beta version at any time, this branch is **not intended to fully support v1.2 Beta yet**.  

## Submitting Pull Requests
If you would like to fix a problem or improve something that we have not done yet, feel free to send us a pull request.  
When submitting a pull request, please make sure you follow these guidelines:
* **Select `mcpe-1.2-beta` as the base branch**. Your commits for the beta version must not be merged into the `master` branch. You can do this when creating your pull request.
* If your pull request resolves an undocumented issue (neither noted by us nor reported in Issues), please **describe the issue in details** as if you are submitting an issue.
* Please **explain your changes thoroughly**, such as why you decide to modify a line, what will happen after we merge your pull request, how we should test your code, etc.

## Submitting Issues
We expect more issues on this branch, so we will be very strict when processing your issue. When submitting issues of code in this branch, please make sure you provide specific and sufficient information.  
* **Check if your issue duplicates any other issue**. Sometimes there are multiple users reporting the same bug. Also, **remember to check closed issues** that we might have fixed.
* No matter how easy your issue can be described, please **always provide a detailed description**. Do not merely say "players can't join server"; instead, try to provide more information beyond that, such as whether your players can see your server running in the server list.
* Please kindly **report the Git commit SHA-1** of GenisysPro you are using, so we can know not only you are using code from this beta branch but also whether a patch that fixes your issue is included in your GenisysPro copy.

GenisysPro
====================

[![GenisysPro](http://i.imgur.com/R8gExma.jpg)](https://genisys.pro)

[English](#english)  
[简体中文](#简体中文)  
[繁體中文](#繁體中文)  
[日本語](#日本語)   
[Dutch](#dutch)  
[Русский](#Русский)  

# English

Introduction
-------------

__GenisysPro is a feature-rich server software for *Minecraft: Pocket Edition* and *Minecraft: Windows 10 Edition*.__  
GenisysPro is based on **[Genisys](https://github.com/iTXTech/Genisys)** with extended functionality. Most of the code was originally written by members of **[iTXTech](https://github.com/orgs/iTXTech/people)**.  

Some of the functionality that Genisys offers:
* Support for multiple client versions
* Extended API for plugins (GeniAPI)
* Optional Xbox Live authentication
* Support for Minecraft: Windows 10 Edition
* Global resource packs
* Generators for **ALL** world types i.e., **Overworld**, **Nether**, and **Ender**
* Integrated DevTools

Supported Version
-------------
Game versions supported by this branch:
- [x] Minecraft PE/Windows 10 v1.2 Beta

Get GenisysPro
-------------
* Download the latest code of this branch from [GitHub](https://github.com/GenisysPro/GenisysPro/archive/mcpe-1.2-beta.zip)  
* Get [PHP binaries](https://genisys.pro/info/download/) and other necessary components.
* Installation instructions can be found in the [Wiki](https://github.com/GenisysPro/GenisysPro/wiki).

Communities
-------------
* [GenisysPro Official Forums](https://genisys.pro/forums/)
* [Discord Group](https://discord.gg/WrKzRNn)
* [QQ Group](https://jq.qq.com/?_wv=1027&k=46Xjsfo)

Donating
-------------
**Head developer's QQ: 1912003473**  
GenisysPro is free software. We appreciate your donation because it helps us make GenisysPro better.

TODO List
-------------

- [ ] Modify file locations to `/src/pocketmine/network/mcpe/` for extended plugin compatibility.
- [ ] Discover all potential modifications that can enhance plugin backward compatibility.
- [ ] Re-work how async workers perform tasks to prevent them from hanging and not performing other tasks.
- [ ] Make modifications to handle Anvil worlds more efficiently to lower resource usage.
- [x] Add Ender generator.
- [ ] Add/Enhance Mob AI with option in yml.

GenisysPro still has a long way to go. We are pleased to receive help from everybody and welcome contributors.  

Help & Support
-------------
Official Doxygen-generated Documentation (unavailable yet)

Other Tools
-------------
* [Pocket Server](https://github.com/fengberd/MinecraftPEServer) - Run PocketMine-MP on Android devices

License
-------------
  	This program is free software: you can redistribute it and/or modify
  	it under the terms of the GNU General Public License as published by
  	the Free Software Foundation, either version 3 of the License, or
  	(at your option) any later version.

  	This program is distributed in the hope that it will be useful,
  	but WITHOUT ANY WARRANTY; without even the implied warranty of
  	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  	GNU General Public License for more details.

  	You should have received a copy of the GNU General Public License
  	along with this program.  If not, see <http://www.gnu.org/licenses/>.


# 简体中文

简介
-------------
__GenisysPro：多功能 Minecraft: PE 和 Minecraft: Windows 10 版服务器软件__  
GenisysPro 基于 **[Genisys](https://github.com/iTXTech/Genisys)** 开发，并且加入了更多功能。大多数原始代码都由 **[iTXTech](https://github.com/orgs/iTXTech/people)** 团队编写。  

Genisys 的部分特色功能：
* 多版本支持
* 插件扩展 API (GeniAPI)
* Xbox Live 验证（可选）
* 支持 Minecraft: Windows 10 版
* 支持全局资源包
* 可生成 **所有** 世界类型的生成器，包括 **主世界**、**下界** 和 **末地**
* 自带 DevTools 开发者工具

支持版本
-------------
此分支目前支持的版本：
- [x] Minecraft PE/Windows 10 v1.2 Beta

获取 GenisysPro
-------------
* 从 [GitHub](https://github.com/GenisysPro/GenisysPro/archive/mcpe-1.2-beta.zip) 获取此分支的最新版本。  
* 获取 [PHP 运行时](https://genisys.pro/info/download/)和其它必要组件。
* 您可在 [Wiki](https://github.com/GenisysPro/GenisysPro/wiki) 上找到安装指南。

注意：**`master` 分支是唯一支持的分支！**  
所有其它分支都是不稳定的测试分支。除非您确定您了解使用测试分支的风险，否则请不要使用 `master` 以外的任何分支。

社区
-------------
* [GenisysPro 官方论坛](https://genisys.pro/forums/)
* [Discord 群组](https://discord.gg/WrKzRNn)
* [QQ 群](https://jq.qq.com/?_wv=1027&k=46Xjsfo)

捐赠
-------------
**开发者 QQ：1912003473**  
GenisysPro 是一个免费、开源的自由软件。我们欢迎并感谢您的捐赠，这将是我们前进的动力。

即将支持
-------------

- [ ] 将与网络相关的文件移动到 `/src/pocketmine/network/mcpe/` 中，增强插件兼容性
- [ ] 查找所有可能会影响插件向下兼容能力的修改
- [ ] 改进异步功能，防止单一任务长期占用线程导致其它任务无法执行
- [ ] 提升 Anvil 地图格式的效率，降低资源占用
- [x] 加入末地生成器
- [ ] 添加/改进生物 AI，在配置文件中添加相关选项

GenisysPro 还有很长的一段路要走。我们欢迎贡献者，并希望能得到帮助！

帮助与支持
-------------
Doxygen 生成的官方文档（暂无）

其它工具
-------------
* [Pocket Server](https://github.com/fengberd/MinecraftPEServer) - 在 Android 设备上开 PocketMine-MP 服

许可协议
-------------
**请遵守 GNU GPLv3 许可协议条款，在重新分发此软件时，向软件接收者提供此软件的源代码，否则将构成侵权行为！**

  	这是一个自由软件。您可以根据自由软件基金会出版的 GNU 通用公共
  	许可证第 3 版的许可条款重新分发并/或修改此软件，或自由选择使用
  	任何更新的版本。

  	分发此软件的目的是希望它能够有用，但此软件不提供任何保证，包括
  	任何默认的适销性和对某特定用途的适用性。有关详情，请参考 GNU
  	通用公共许可证条款。

  	您应该已经随此软件收到一份 GNU 通用公共许可证的副本。如果没有，
  	请访问 <http://www.gnu.org/licenses/>。

# 繁體中文

介紹
-------------
__GenisysPro：功能豐富的 Minecraft: PE 與 Minecraft: Windows 10 Edition 伺服器軟體__  
GenisysPro 基於 **[Genisys](https://github.com/iTXTech/Genisys)** 開發，并且加入更多機能。大部分原始程式碼皆爲 **[iTXTech](https://github.com/orgs/iTXTech/people)** 團隊所編寫。  

Genisys 的部分特別機能：
* 同時支援多個版本連線
* 插件擴充 API (GeniAPI)
* 選擇性的 Xbox Live 驗證開關
* 支援 Minecraft: Windows 10 Edition
* 支援全球資源包
* 可生成 **所有** 世界類型的生成器，包含 **主世界**、**下界** 和 **末路之地**
* 集成 DevTools 開發人員工具

支援的版本
-------------
此分支當前支援的版本：
- [x] Minecraft PE/Windows 10 v1.2 Beta

獲得 GenisysPro
-------------
* 在 [GitHub](https://github.com/GenisysPro/GenisysPro/archive/mcpe-1.2-beta.zip) 下載此分支的最新版。  
* 下載 [PHP 二進制碼](https://genisys.pro/info/download/)和其他必備的組件。
* 您可以在 [Wiki](https://github.com/GenisysPro/GenisysPro/wiki) 找到配置指南。

注意：**`master` 分支是唯一支援的分支！**  
所有其它分支皆爲不穩定的測試分支。除非您確定您知曉使用測試分支的風險，否則請勿使用非 `master` 的任何分支。

社區
-------------
* [GenisysPro 官方論壇](https://genisys.pro/forums/)
* [Discord 群組](https://discord.gg/WrKzRNn)
* [QQ 群](https://jq.qq.com/?_wv=1027&k=46Xjsfo)

捐贈
-------------
**開發者 QQ：1912003473**  
GenisysPro 是一款免費、開放源代碼的自由軟體。我們歡迎並感謝您的捐贈，這是我們前進的動力。

將要支援
-------------

- [ ] 將與網路相關的檔案移動到 `/src/pocketmine/network/mcpe/` 中，加强插件相容性
- [ ] 查找所有可能會影響插件向下相容能力的修改
- [ ] 改進異步功能，防止單一任務長期占用執行緒導致其他任務無法執行
- [ ] 提升 Anvil 地圖格式的效能，降低資源占用
- [x] 添加末路之地生成器
- [ ] 添加/改進生物 AI，在配置檔中添加相關設定

GenisysPro 還有很長的一段路要走。我們歡迎貢獻者，並希望得到幫助！

幫助與支援
-------------
由 Doxygen 產生的官方檔案（暫無）

其它實用程式
-------------
* [Pocket Server](https://github.com/fengberd/MinecraftPEServer) - 於 Android 裝置上運作 PocketMine-MP 伺服器

授權條款
-------------
    本程式是一個自由軟體。您可以遵照自由軟體基金會出版的 GNU 通用
    公共許可證第 3 版的許可證條款重新發佈並/或修改此軟體，或自由
    選擇使用任何更新的版本。

    發佈這一程式的目的是希望它有用，但此軟件無任何擔保，包括任何
    默許的適銷性和對某特定目的的適用性。有關更詳細的情況，請參考
    GNU 通用公共許可證條款。

    您應該已經和程式一起收到一份 GNU 通用公共許可證的副本。如果還
    沒有，請造訪 <http://www.gnu.org/licenses/>。


# 日本語

前書き
-------------

__GenisysProは、*Minecraft: Pocket Edition* および *Minecraft: Windows 10 Edition* 用の豊富な機能を備えたサーバーソフトウェアです。__  
GenisysProは **[Genisys](https://github.com/iTXTech/Genisys)** をベースとし、拡張機能を備えています。 コードのほとんどは、もともと **[iTXTech](https://github.com/orgs/iTXTech/people)** のメンバーによって書かれたものです。

Genisysが提供する機能の一部:
* 複数のクライアントバージョンのサポート
* 拡張プラグイン用のAPI（GeniAPI）
* オプションのXbox Live認証
* Minecraftのサポート：Windows 10 Edition
* グローバルリソースパック
* 世界のすべてのタイプ、すなわち、Overworld、Nether、Enderの成形機
* 組み込まれたDevTools

サポートされているバージョン
-------------
<!-- `master` ブランチにてサポートしているバージョン: -->
- [x] Minecraft PE/Windows 10 v1.2 ベータ版

GenisysProを入手する
-------------
* 最新のコードを [GitHub](https://github.com/GenisysPro/GenisysPro/archive/mcpe-1.2-beta.zip) からダウンロードする。
* [PHPバイナリーを入手](https://genisys.pro/info/download/)
* インストール手順は [Wiki](https://github.com/GenisysPro/GenisysPro/wiki) をご覧ください。

注意: **`master`ブランチは公式にサポートされている唯一のブランチです。**  
他のすべてのブランチはテスト中であり、不安定である可能性があります。 リスクがあることを理解していない限り、他のブランチからビルドを使用しないでください。

討論
-------------
* [GenisysPro Official Forums](https://genisys.pro/forums/)
* [Discordグループ](https://discord.gg/WrKzRNn) Japaneseチャンネルでは日本語で質問をすることができます。
* [QQグループ](https://jq.qq.com/?_wv=1027&k=46Xjsfo)

寄付
-------------
**Head developer's QQ: 1912003473**  
GenisysProはフリーソフトウェアです。 寄付をいただきありがとうございます。GenisysProをより良くするためです。

TODO(やること)リスト
-------------

- [ ] 拡張されたプラグインの互換性のため、ファイルの場所を `/src/pocketmine/network/mcpe/`に変更
- [ ] プラグインの下位互換性を向上させる可能性のあるすべての修正を発見してください。
- [ ] 非同期作業者がハングしたり他のタスクを実行したりしないようにするために、非同期作業者がタスクを実行する方法を再調整できるようにする
- [ ] Anvilの世界をより効率的に処理するための修正を行い、リソースの使用量を削減できるようにする
- [x] Endの成形機の追加
- [ ] yamlのオプションでMob AIを追加/強化できるようにする

GenisysProはまだまだ道のりがあります。 皆様のご協力と寄付を歓迎しております。  

ヘルプ & サポート
-------------
Doxygenで生成された公式文書（まだ入手できません）

その他のツール
-------------
* [Pocket Server](https://github.com/fengberd/MinecraftPEServer) - PocketMine-MPをAndroid端末上で動かせるようにします。

ライセンス
-------------
	このプログラムはフリーソフトウェアです：
	あなたはそれを再配布したり、変更したりすることができます
	GNU一般公衆利用許諾契約書の条項の下で
	フリーソフトウェア財団、ライセンスのバージョン3、または
	（あなたのオプションで）すべてのそれ以降のバージョン。

	このプログラムは有用であることを願って配布されており、
	ただし、いかなる保証もありません。 黙示の保証なしでも
	商品性または特定の目的への適合性を保証するものではありません。 を参照してください
	詳細については、GNU General Public Licenseを参照してください。

	このプログラムと共にGNU General Public Licenseのコピーを受け取ったはずです。
	そうでない場合は、<http://www.gnu.org/licenses/licenses.ja.html>を参照してください。


# Dutch

Invoering
-------------

__GenisysPro is een toekomst rijke serversoftware voor *Minecraft: Pocket Edition* en *Minecraft: Windows 10 Edition*.__
GenisysPro is gebaseerd op **[Genisys](https://github.com/iTXTech/Genisys)** met uitgebreide functionaliteit. Het grootste deel van de code is oorspronkelijk geschreven door leden van **[iTXTech](https://github.com/orgs/iTXTech/people)**.

Enkele functies die Genisys biedt:
* Ondersteuning voor meerdere clientversies
* Uitgebreide API voor plugins (GeniAPI)
* Optionele Xbox Live-verificatie
* Ondersteuning voor Minecraft: Windows 10 Edition
* Global resource packs

Ondersteunde versie
-------------
<!-- Game-versies ondersteund door `master` -fork: -->
- [x] Minecraft PE/Windows 10 v1.2 Beta

GenisysPro krijgen
-------------
* Download de laatste code van [GitHub](https://github.com/GenisysPro/GenisysPro/archive/mcpe-1.2-beta.zip).  
* Krijg [PHP binaries](https://genisys.pro/info/download/) en andere benodigde componenten.
* Installatie-instructies zijn te vinden in de [Wiki](https://github.com/GenisysPro/GenisysPro/wiki).


OPMERKING: ** De `master`-fork is de enige officieel ondersteunde fork. **
Alle andere takken zijn in het testen en kunnen instabiel zijn. Gebruik geen bouwwerken van andere takken, tenzij u zeker weet dat u de risico's begrijpt.

Discussie
-------------
* [Discord Group](https://discord.gg/WrKzRNn)
* [QQ groep](https://jq.qq.com/?_wv=1027&k=46Xjsfo)

Het doneren
-------------
** Hoofdontwikkelaar QQ: 1912003473 **
GenisysPro is gratis software. We waarderen uw donatie, omdat het ons helpt om GenisysPro beter te maken.

Te doen lijst
-------------

- [x] Baken!
- [x] Folder Plugin Loader
- [x] Meer blokken
- [x] Anvil
- [x] Global resource packs

GenisysPro heeft nog een lange weg. We zijn blij om hulp van iedereen te ontvangen en welkom medewerkers.

Hulp & Ondersteuning
-------------
Officiële Doxygen-gegenereerde Documentatie (nog niet beschikbaar)

Overige gereedschappen
-------------
* [Pocket Server](https://github.com/fengberd/MinecraftPEServer) - Run PocketMine-MP op Android-apparaten

License
-------------
  	This program is free software: you can redistribute it and/or modify
  	it under the terms of the GNU General Public License as published by
  	the Free Software Foundation, either version 3 of the License, or
  	(at your option) any later version.

  	This program is distributed in the hope that it will be useful,
  	but WITHOUT ANY WARRANTY; without even the implied warranty of
  	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  	GNU General Public License for more details.

  	You should have received a copy of the GNU General Public License
  	along with this program.  If not, see <http://www.gnu.org/licenses/>.

# Русский

Введение
-------------

__GenisysPro - многофункциональное серверное программное обеспечение для *Minecraft: Pocket Edition* и *Minecraft: Windows 10 Edition*.__  
GenisysPro основан на **[Genisys](https://github.com/iTXTech/Genisys)** с расширенной функциональностью. Большая часть кода изначально была написана участниками **[iTXTech](https://github.com/orgs/iTXTech/people)**.  

Некоторые функции, предлагаемые Genisys:
* Поддержка нескольких клиентских версий
* Расширенный API для плагинов (GeniAPI)
* Дополнительная проверка подлинности Xbox Live
* Поддержка Minecraft: Windows 10 Edition
* Глобальные пакеты ресурсов

Поддерживаемая версия
-------------
<!-- Игровые версии, поддерживаемые ветвью `master`: -->
- [x] Minecraft PE/Windows 10 v1.2 Beta

Установка и получение GenisysPro
-------------
* Загрузите исходный код из [GitHub](https://github.com/GenisysPro/GenisysPro/archive/mcpe-1.2-beta.zip).  
* Загрузите [PHP библиотеки](https://genisys.pro/info/download/) и другие необходимые компоненты.
* Инструкции по установке можно найти на [Вики](https://github.com/GenisysPro/GenisysPro/wiki).

Обсуждение
-------------
* [Группа Discord](https://discord.gg/WrKzRNn)
* [Группа QQ](https://jq.qq.com/?_wv=1027&k=46Xjsfo)

Пожертвование
-------------
**Главный разработчик QQ: 1912003473**  
GenisysPro - бесплатное программное обеспечение. Мы ценим ваше пожертвование, потому что это помогает нам улучшить GenisysPro.

Список задач
-------------

- [x] Маяки!
- [x] Загрузчик плагинов из исходных папок
- [x] Больше блоков
- [x] Наковальни
- [x] Глобальные пакеты ресурсов (ресурс паки)

GenisysPro еще предстоит пройти долгий путь. Мы рады получить помощь от всех и приветствовать участников.

Помощь и Поддержка
-------------
Официальная документация, созданная Doxygen (пока недоступна)


Другие Инструменты
-------------
* [Pocket Server](https://github.com/fengberd/MinecraftPEServer) - Позволит запустить PocketMine-MP и GenisysPro на устройствах Android

Лицензия
-------------
	Эта программа является свободным программным обеспечением: вы можете распространять ее и / или изменять
	Это в соответствии с условиями GNU General Public License, опубликованной
	Фондом бесплатного программного обеспечения, либо вариант 3 лицензии, либо
	(По вашему выбору) любой более поздней версии.

	Эта программа распространяется в надежде, что она будет полезна,
	Но БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ; Без подразумеваемой гарантии
	КОММЕРЧЕСКОЙ ЦЕННОСТИ или ПРИГОДНОСТИ ДЛЯ ОПРЕДЕЛЕННОЙ ЦЕЛИ. См.
	GNU General Public License для получения более подробной информации.

	Вы должны были получить копию общедоступной лицензии GNU
	Наряду с этой программой. Если нет, см. <http://www.gnu.org/licenses/>.
