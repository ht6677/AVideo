<?php
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');


require_once dirname(__FILE__) . '/../../videos/configuration.php';

$objMob = YouPHPTubePlugin::getObjectData("MobileManager");
$video = Video::getVideo("", "viewableNotUnlisted", true, false, true);
if (empty($video)) {
    $video = Video::getVideo("", "viewableNotUnlisted", true, true);
}
$images = Video::getImageFromFilename_($video['filename']);

$video['Poster'] = !empty($objMob->portraitImage)?$images->posterPortrait:$images->poster;
$video['Thumbnail'] = !empty($objMob->portraitImage)?$images->posterPortraitThumbs:$images->thumbsJpg;
$video['imageClass'] = !empty($objMob->portraitImage)?"portrait":"landscape";
die(json_encode($video));