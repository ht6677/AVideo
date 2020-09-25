<?php

global $global;
require_once $global['systemRootPath'] . 'plugin/Plugin.abstract.php';

class TheaterButton extends PluginAbstract {

    public function getTags() {
        return array(
            PluginTags::$FREE,
            PluginTags::$PLAYER,
            PluginTags::$LAYOUT,
        );
    }
    public function getDescription() {
        return "Add next theater switch button to the control bar";
    }

    public function getName() {
        return "TheaterButton";
    }

    public function getUUID() {
        return "f7596843-51b1-47a0-8bb1-b4ad91f87d6b";
    }
    
    public function getPluginVersion() {
        return "1.1";   
    }

    public function getEmptyDataObject() {
        $obj = new stdClass();
        $obj->show_switch_button = true;
        $obj->compress_is_default = false;
        return $obj;
    }
    
    public function getHeadCode() {
        global $global;
        if (!$this->showButton()) {
            return "";
        }
        $tmp = "mainVideo";
        if(!empty($_SESSION['type'])){
            if(($_SESSION['type']=="audio")||($_SESSION['type']=="linkAudio")){
                $tmp = "mainAudio";
            }
        }
        $css = '<link href="' . $global['webSiteRootURL'] . 'plugin/TheaterButton/style.css?'. filemtime($global['systemRootPath'] . 'plugin/TheaterButton/style.css').'" rel="stylesheet" type="text/css"/>';
        $css .= '<script>var videoJsId = "'.$tmp.'";</script>';
        return $css;
    }
    public function getJSFiles(){
        global $global, $autoPlayVideo, $isEmbed;
        if (!$this->showButton()) {
            return "";
        }
        $obj = $this->getDataObject();
        if(!empty($obj->show_switch_button)){
            return array("plugin/TheaterButton/script.js","plugin/TheaterButton/addButton.js");
        }
        return array("plugin/TheaterButton/script.js");
    }
    public function getFooterCode() {
        global $global, $autoPlayVideo, $isEmbed;
        if (!$this->showButton()) {
            return "";
        }
        $obj = $this->getDataObject();
        $js = '';
        if(empty($obj->show_switch_button)){
            if($obj->compress_is_default){
                $js .= '<script>$(document).ready(function () {if (typeof compress === "function" && videojs){compress(videojs);}});</script>';
            }else{
                $js .= '<script>$(document).ready(function () {if (typeof expand === "function" && videojs){expand(videojs);}});</script>';
            }
        }
        
        return $js;
    }
    
    private function showButton(){
        if (isMobile() || isEmbed()) {
            return false;
        }
        if(isVideo() || isLive()){
            return true;
        }
        return false;
    }
    static function isCompressed(){
        if(empty($_COOKIE['compress'])){
            $obj = AVideoPlugin::getDataObject('TheaterButton');
            return $obj->compress_is_default?true:false;
        }
        return ($_COOKIE['compress']==='false')?false:true;
    }

}
