<?php

/*
THIS FILE IS PART OF THE GLIFCOS PROJECT BY @HOTFIREYDEATH.

THIS PROJECT IS LICENSED UNDER THE MIT LICENSE (MIT). A COPY OF 
THE LICENSE IS AVAILABLE WITH YOUR DOWNLOAD (LICENSE.txt).

      ___                                     ___           ___           ___           ___     
     /\__\                                   /\__\         /\__\         /\  \         /\__\    
    /:/ _/_                     ___         /:/ _/_       /:/  /        /::\  \       /:/ _/_   
   /:/ /\  \                   /\__\       /:/ /\__\     /:/  /        /:/\:\  \     /:/ /\  \  
  /:/ /::\  \   ___     ___   /:/__/      /:/ /:/  /    /:/  /  ___   /:/  \:\  \   /:/ /::\  \ 
 /:/__\/\:\__\ /\  \   /\__\ /::\  \     /:/_/:/  /    /:/__/  /\__\ /:/__/ \:\__\ /:/_/:/\:\__\
 \:\  \ /:/  / \:\  \ /:/  / \/\:\  \__  \:\/:/  /     \:\  \ /:/  / \:\  \ /:/  / \:\/:/ /:/  /
  \:\  /:/  /   \:\  /:/  /   ~~\:\/\__\  \::/__/       \:\  /:/  /   \:\  /:/  /   \::/ /:/  / 
   \:\/:/  /     \:\/:/  /       \::/  /   \:\  \        \:\/:/  /     \:\/:/  /     \/_/:/  /  
    \::/  /       \::/  /        /:/  /     \:\__\        \::/  /       \::/  /        /:/  /   
     \/__/         \/__/         \/__/       \/__/         \/__/         \/__/         \/__/    
*/


namespace glifcos;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\player\PlayerJoinEvent;
use glifcos\mainw\synctask;

class glifcos extends PluginBase implements Listener {
    public $direct;
    public function onEnable(){
        if (!is_dir($this->getDataFolder())){
            mkdir($this->getDataFolder());
            $this->saveDefaultConfig();
        }
        $res = $this->runServerCheck();
        if (!$res){
            $this->getLogger()->warning("Glifcos could not verify the server. Please check your info in the config file.");
            $this->getServer()->getPluginManager()->disablePlugin($this->getServer()->getPluginManager()->getPlugin("Glifcos-p"));
            return true;
        }
        $this->sellServerInfo();
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new synctask($this), 10);
        // separate for notifying that the server came up :P
        fopen($this->getConfig()->get("glifcos-domain")."?type=startup", "r");
        // ===
    }
    private function runServerCheck(){
        $domain = $this->getConfig()->get("glifcos-domain");
        $result = $this->url_exists($domain);
        if ($result === false){
            return false;
        }
        else{
            $data = fopen($domain."?type=ping", "r");
            $data2 = fread($data, 5);
            if ($data2 == "apple"){
                return true;
            }
            else{
                return false;
            }
        }
    }
    private function sellServerInfo(){
        // Don't take this angrily!! I'm just syncing
        // data with the webserver >~<
        $domain = $this->getConfig()->get("glifcos-domain");
        // this is to get the real external ip..
        
        $dat = array("ip" => json_decode(file_get_contents("http://api.ipify.org/?format=json")
        , true)["ip"], 
        "port" => $this->getServer()->getPort(), 
        "api" => $this->getServer()->getApiVersion(),
        "pm-v" => $this->getServer()->getPocketMineVersion(),
        "servern" => $this->getServer()->getServerName(),
        "motd" => $this->getServer()->getMotd(),
        );
        $compile = base64_encode(json_encode($dat));
        $data = fopen($domain."?type=updatedata&data=".$compile, "r");
        $this->getLogger()->info("Datasync sent to webserver.");
    }
    public function renderCommand($command){
        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
    }
    public function onJoin(PlayerJoinEvent $event){
        $domain = $this->getConfig()->get("glifcos-domain");
    }
    public function onDisable(){
        if (!file_exists($this->getConfig()->get("glifcos-domain"))){
            return true;
        }
        fopen($this->getConfig()->get("glifcos-domain")."?type=closedown", "r");
        $this->getLogger()->info("Datasync sent to webserver.");
    }
    public function scanDirectory($base){
        $entire = array();
        foreach(scandir($base) as $stuff){
                if (is_file($base."/".$stuff)){
                    $entire[$base."/".$stuff] = array("type" => "file", "lastmodded" =>
                    date("F d, Y H:i:s", filemtime($base."/".$stuff)), "ext" => pathinfo($base."/".$stuff, 
                    PATHINFO_EXTENSION), "instantname" => $stuff, "data" => 
                    mb_convert_encoding(file_get_contents($base."/".$stuff), "UTF-8", "UTF-8"),
                    "size" => filesize($base."/".$stuff));
                    if ($entire[$base."/".$stuff]["ext"] === "phar"){
                        unset($entire[$base."/".$stuff]["data"]);
                    }
                }
                elseif (is_dir($base."/".$stuff)){
                    $entire[$base."/".$stuff] = array("type" => "dir", "name" => $stuff);
                    $pointer = $base."/".$stuff;
                }
        }
        return $entire;
    }
    public function FolderRm($path){
        // RESOURCE FOUND AT STACKOVERFLOW.
        if (is_dir($path) === true){
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file){
                $this->FolderRm(realpath($path) . '/' . $file);
            }
            return rmdir($path);
        }
        else if (is_file($path) === true){
            return unlink($path);
        }
        return false;
    }
    public function FolderCp($src, $dst) { 
        // RESOURCE FOUND AT STACKOVERFLOW.
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->FolderCp($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                }
            } 
        } 
        closedir($dir); 
    } 
    public function url_exists($url){
        // RESOURCE FOUND AT PHP.NET
        // http://php.net/manual/en/function.file-exists.php#78656
        $url = str_replace("http://", "", $url);
        if (strstr($url, "/")) {
            $url = explode("/", $url, 2);
            $url[1] = "/".$url[1];
        } else {
            $url = array($url, "/");
        }

        $fh = fsockopen($url[0], 80);
        if ($fh) {
            fputs($fh,"GET ".$url[1]." HTTP/1.1\nHost:".$url[0]."\n\n");
            if (fread($fh, 22) == "HTTP/1.1 404 Not Found") { return false; }
            else { return true;    }

        } else { return false;}
    }
}