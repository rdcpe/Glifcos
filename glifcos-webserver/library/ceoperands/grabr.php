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

class grabr {
    public static function getCurrentMemory($base_dir){
        /**
         * Returns current server memory usage (megabytes).
         * @param $base_dir The webserver base directory
         **/
         $data = json_decode(file_get_contents($base_dir."/core.json"), true);
         return $data["serverdata"]["currentmemory"];
    }
    public static function getTotalMemory($base_dir){
        /**
         * Returns total server memory limit (megabytes).
         * @param $base_dir The webserver base directory
         **/
         $data = json_decode(file_get_contents($base_dir."/core.json"), true);
         return $data["serverdata"]["totalmemory"];
    }
    public static function getCurrentCPU($base_dir){
        /**
         * Returns current server CPU usage (megabytes).
         * @param $base_dir The webserver base directory
         **/
         $data = json_decode(file_get_contents($base_dir."/core.json"), true);
         return $data["serverdata"]["currentcpu"];
    }
    public static function getPMBuild($base_dir){
        /**
         * Returns PocketMine build.
         * @param $base_dir The webserver base directory
         **/
         $data = json_decode(file_get_contents($base_dir."/core.json"), true);
         if ($data["serverdata"]["pm-v"] === "1.0dev"){
             return "ImagicalMine v1.0dev";
         }
         elseif ($data["serverdata"]["genisys"]){
             return "Genisys ".$data["serverdata"]["pm-v"];
         }
         else{
             return "PocketMine v".$data["serverdata"]["pm-v"];
         }
    }
    public static function isServerOnline($base_dir){
        /**
         * Returns true if it is online, false if not online (server).
         * @param $base_dir The webserver base directory
         **/
        $data = json_decode(file_get_contents($base_dir."/core.json"), true);
        if ($data["serverdata"]["status"] === "closed"){
            return false;
        }
        else{
            return true;
        }
    }
    public static function getTotalPlayers($base_dir){
        /**
         * Returns total player limit.
         * @param $base_dir The webserver base directory
         **/
         $data = json_decode(file_get_contents($base_dir."/core.json"), true);
         return $data["playerquery"]["total"];
    }
    public static function getCurrentPlayerAM($base_dir){
        /**
         * Returns current player amount.
         * @param $base_dir The webserver base directory
         **/
         $data = json_decode(file_get_contents($base_dir."/core.json"), true);
         return $data["playerquery"]["currentamount"];
    }
    public static function getPlayerList($base_dir){
        /**
         * Returns list of players.
         * @param $base_dir The webserver base directory
         **/
         $data = json_decode(file_get_contents($base_dir."/core.json"), true);
         return $data["playerquery"]["list"];
    }
    public static function getConsole($base_dir){
        /**
         * Returns the console (technically the server.log).
         * @param $base_dir The webserver base directory
         **/
        $data = json_decode(file_get_contents($base_dir."/core.json"), true);
        return $data["console"];
    }
    public static function getPlugins($base_dir){
        /**
         * Returns the list of plugins (name).
         * @param $base_dir The webserver base directory
         **/
        $data = json_decode(file_get_contents($base_dir."/core.json"), true);
        return $data["serverdata"]["plugins"];
    }
    public static function isPluginEnabled($base_dir, $pluginname){
        /**
         * Returns true if plugin is enabled on server, else false.
         * @param $base_dir The webserver base directory
         * @param $pluginname String
         **/
        $data = json_decode(file_get_contents($base_dir."/core.json"), true);
        return $data["serverdata"]["p-enabledor"][$pluginname];
    }
}