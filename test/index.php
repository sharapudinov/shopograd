<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");

$rsResult = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => "1","!UF_TAOBAO_ID"=>false), false, $arSelect = array("UF_TAOBAO_ID"));
$counter=0;
while ($arResult = $rsResult -> GetNext())
{
    $counter++;
    print "<pre>" . print_r($arResult, true) . "</pre>";
}
echo $counter;
?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>