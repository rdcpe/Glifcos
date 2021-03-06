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

require $_COOKIE["cl"]."/library/main/guardian.php";
require $_COOKIE["cl"]."/library/ceoperands/talk.php";

if (isset($_POST["sthap"])){
    // EDIT FILE
    $filename = $_COOKIE["filenme"];
    setcookie("filenme", "", time()); //removes useless file cookie.
    talk::updateFile($filename, $_POST["sthap"], $_COOKIE["cl"]);
    sleep(4);
    talk::resetTalk($_COOKIE["cl"]);
    echo '
    <script> window.location = "mainfile.php?rf=filechanged"; </script>
    ';
}
elseif (isset($_GET["addflag"])){
    // NAVIGATE ANOTHER FOLDER
    setcookie("curflags", $_COOKIE["curflags"]."/".$_GET["addflag"]."/");
    echo '
    <script> window.location = "mainfile.php"; </script>
    ';
}
elseif (isset($_GET["rf"])){
    if ($_GET["rf"] === "delete"){
        // DELETE FILE
        talk::deleteFile($_COOKIE["filedelname"], $_COOKIE["cl"]);
        setcookie("filedelname", "", time());
        sleep(4);
        talk::resetTalk($_COOKIE["cl"]);
        echo '
        <script> window.location = "mainfile.php?rf=filedelled"; </script>
        ';
    }
}
elseif (isset($_POST["rename"])){
    // RENAME FILE
    $cos = pathinfo($_COOKIE["filenme"]);
    talk::renameFile($_COOKIE["filenme"], $cos["dirname"].$_POST["rename"], $_COOKIE["cl"]);
    sleep(4);
    talk::resetTalk($_COOKIE["cl"]);
    echo '
    <script> window.location = "mainfile.php?rf=filerenamed"; </script>
    ';
}
elseif (isset($_GET["remove"])){
    // REMOVE FILE
    setcookie("curflags", dirname($_COOKIE["curflags"]));
    echo '
    <script> window.location = "mainfile.php"; </script>
    ';
}
elseif (isset($_POST["movedir"])){
    // MOVE DIRECTORY
    talk::moveFile($_COOKIE["filenme"], $_POST["movedir"], $_COOKIE["cl"]);
    sleep(4);
    talk::resetTalk($_COOKIE["cl"]);
    echo '
    <script> window.location = "mainfile.php?rf=filemoved"; </script>
    ';
}
elseif (isset($_POST["copydir"])){
    // COPY DIRECTORY
    talk::copyFile($_COOKIE["filenme"], $_POST["copydir"], $_COOKIE["cl"]);
    sleep(4);
    talk::resetTalk($_COOKIE["cl"]);
    echo '
    <script> window.location = "mainfile.php?rf=filecopied"; </script>
    ';
}
elseif (isset($_POST["movedir"])){
    // MOVE DIRECTORY
    talk::moveFile($_COOKIE["filenme"], $_POST["copydir"], $_COOKIE["cl"]);
    sleep(4);
    talk::resetTalk($_COOKIE["cl"]);
    echo '
    <script> window.location = "mainfile.php?rf=filemoved"; </script>
    ';
}
elseif (isset($_POST["fname"])){
    // MAKE FOLDER
    talk::makeNewFile($_POST["fname"], $_COOKIE["curflags"], $_COOKIE["cl"], true);
    sleep(4);
    talk::resetTalk($_COOKIE["cl"]);
    echo '
    <script> window.location = "mainfile.php?rf=creationac"; </script>
    ';
}
elseif (isset($_POST["finame"])){
    // MAKE FILE
    talk::makeNewFile($_POST["finame"], $_COOKIE["curflags"], $_COOKIE["cl"], false);
    sleep(4);
    talk::resetTalk($_COOKIE["cl"]);
    echo '
    <script> window.location = "mainfile.php?rf=creationac"; </script>
    ';
}
else{
    die("Invalid connection!");
}