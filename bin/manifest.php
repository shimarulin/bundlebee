<?php

$targetDir               = $argv[1];
$targetFile              = $argv[2];
$mainFile                = $argv[3];
$nameSystem              = $argv[4];
$typeAttr                = $argv[5];
$forVersionAttr          = $argv[6];
$methodAttr              = $argv[7];
$clientAttr              = $argv[8];
$groupAttr               = $argv[9];
$nameTextNode           = $argv[10];
$authorTextNode         = $argv[11];
$authorEmailTextNode    = $argv[12];
$authorUrlTextNode      = $argv[13];
$copyrightTextNode      = $argv[14];
$licenseTextNode        = $argv[15];
$versionTextNode        = $argv[16];
$creationDateTextNode   = $argv[17];
$descriptionTextNode    = $argv[18];

//Создает XML-строку и XML-документ при помощи DOM
$dom = new DomDocument('1.0','UTF-8');

//добавление корня - <extension> и установка доступных атрибутов для него
$extension = $dom->appendChild($dom->createElement('extension'));
$extension->setAttribute('type',$typeAttr);
$extension->setAttribute('version',$forVersionAttr);
$extension->setAttribute('method',$methodAttr);
if ($clientAttr!=false) {
    $extension->setAttribute('client',$clientAttr);
}
if ($groupAttr!=false) {
    $extension->setAttribute('group',$groupAttr);
}

//добавление дочерних элементов
$name = $extension->appendChild($dom->createElement('name'));
$author = $extension->appendChild($dom->createElement('author'));
$authorEmail = $extension->appendChild($dom->createElement('authorEmail'));
$authorUrl = $extension->appendChild($dom->createElement('authorUrl'));
$copyright = $extension->appendChild($dom->createElement('copyright'));
$license = $extension->appendChild($dom->createElement('license'));
$version = $extension->appendChild($dom->createElement('version'));
$creationDate = $extension->appendChild($dom->createElement('creationDate'));
$description = $extension->appendChild($dom->createElement('description'));

// Добавляем в документ список файлов
if ($handle = opendir($targetDir) && $typeAttr!="package") {
    $files = $extension->appendChild($dom->createElement('files'));
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." && is_dir($targetDir."/".$file)) {
            $folder = $files->appendChild($dom->createElement('folder'));
            $folder->appendChild($dom->createTextNode($file));
        } elseif ($file != "." && $file != ".." && is_file($targetDir."/".$file)) {
            $filename = $files->appendChild($dom->createElement('filename'));
            $filename->appendChild($dom->createTextNode($file));
            if ($file == $mainFile) {
                $filename->setAttribute($typeAttr,$nameSystem);
            }
        }
    }
    closedir($handle);
} elseif ($typeAttr="package") {
    if ($handle = opendir($targetDir."/modules")) {
        $files = $extension->appendChild($dom->createElement('files'));
        $files->setAttribute("folder","modules");
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $filename = $files->appendChild($dom->createElement('file'));
                $filename->appendChild($dom->createTextNode($file));
                $filename->setAttribute("type","module");
                $filename->setAttribute("id","");
                $filename->setAttribute("client","");
            }
        }
        closedir($handle);
    }
}

//foreach (glob($targetDir."/*") as $file) {
//    $filename = $files->appendChild($dom->createElement('filename'));
//    $filename->appendChild($dom->createTextNode($file));
//}

// Принудительно добавляем еще не созданный манифест
$filename = $files->appendChild($dom->createElement('filename'));
$filename->appendChild($dom->createTextNode($targetFile));





// добавление элемента <title> в <name>
//$title = $name->appendChild($dom->createElement('title'));

// добавление текстовых узлов в элементы
$name->appendChild($dom->createTextNode($nameTextNode));
$author->appendChild($dom->createTextNode($authorTextNode));
$authorEmail->appendChild($dom->createTextNode($authorEmailTextNode));
$authorUrl->appendChild($dom->createTextNode($authorUrlTextNode));
$copyright->appendChild($dom->createTextNode($copyrightTextNode));
$license->appendChild($dom->createTextNode($licenseTextNode));
$version->appendChild($dom->createTextNode($versionTextNode));
$creationDate->appendChild($dom->createTextNode($creationDateTextNode));
$description->appendChild($dom->createTextNode($descriptionTextNode));

//генерация xml
$dom->formatOutput = true; // установка атрибута formatOutput domDocument в значение true
// save XML as string or file
$dom->saveXML(); // передача строки в test1
$dom->save($targetDir."/".$targetFile); // сохранение файла
?>