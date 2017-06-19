param (
	[switch]$Loop = $false
)

if(Test-Path "bin\php\php.exe"){
	$env:PHPRC = ""
	$binary = "bin\php\php.exe"
}else{
	$binary = "php"
}

if(Test-Path "Genisys*.phar"){
    # Windows PowerShell does not recognize file path with wildcard,
    # so we need to get the exact file path.
    foreach($filename in Get-ChildItem Genisys*.phar -Name){
        $file = "'$filename'" # This allows the file name to contain space
        break
    }
}elseif(Test-Path "PocketMine-MP.phar"){
	$file = "PocketMine-MP.phar"
}elseif(Test-Path "src\pocketmine\PocketMine.php"){
	$file = "src\pocketmine\PocketMine.php"
}elseif(Test-Path "Genisys.phar"){
	$file = "Genisys.phar"
}else{
	echo "[ERROR] Couldn't find a valid Genisys installation."
	pause
	exit 1
}

function StartServer{
	$command = $binary + " " + $file + " --enable-ansi"
	chcp 65001
	iex $command
}

$loops = 0

StartServer

while($Loop){
	if($loops -ne 0){
		echo ("Restarted " + $loops + " times")
	}
	$loops++
	echo "To escape the loop, press CTRL+C now. Otherwise, wait 5 seconds for the server to restart."
	echo ""
	Start-Sleep 5
	StartServer
}
