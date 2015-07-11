<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
/**
 * Created by PhpStorm.
 * User: Asus-
 * Date: 02.07.2015
 * Time: 1:08
 */

$categories=&file('categories.csv');
$categories=array_filter($categories,function($arg){
    return $arg!=";;;;\n";
});
foreach($categories as &$category)
{
    $category = explode(';', $category);
    $category[0] = explode(']', explode("[", $category[0])[1])[0];
    $category[1] = explode('[', $category[1]);
    $category[1][0]=trim($category[1][0]);
    $category[1][1] = str_replace(']', '',$category[1][1]);
}

$categories=array_filter($categories,function($arg){
    return $arg[1][0]!=='';
});
define('LOG_FILENAME','1.log');

CModule::IncludeModule('iblock');
$section=new CIBlockSection;
foreach ($categories as &$category){
    $arFields=array(
        'UF_TAOBAO_ID'=>$category[0],
    );
    if($category[1][1]!=''){
        $result=$section->Update($category[1][1],$arFields);
    }
    else {
        $dbSec=CIBlockSection::GetList(array(),array('NAME'=>$category[1][0]));
        $sec=$dbSec->GetNext();
        $result=$section->Update($sec["ID"],$arFields);
    }
}

/*$arFields=array(
    'UF_TAOBAO_ID'=>$category[0],
    'UF_SHOW_LEFT_MENU'=>'да'
);
$section->Update(2392,$arFields);*/

/*$dbSec=CIBlockSection::GetList(array(),array('IBLOCK_ID'=>1));
while($sec=$dbSec->GetNext()){

}*/


//test_dump($categories);
