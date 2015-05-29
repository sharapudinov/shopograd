<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arStructured = array();
$sectionLink = array();
$arStructured['ROOT'] = array();
$sectionLink[0] = &$arStructured['ROOT'];
foreach ($arResult['SECTIONS'] as $arSection) {
    $sectionLink[intval($arSection['IBLOCK_SECTION_ID'])]['CHILD'][$arSection['ID']] = $arSection;
    $sectionLink[$arSection['ID']] = &$sectionLink[intval($arSection['IBLOCK_SECTION_ID'])]['CHILD'][$arSection['ID']];
}
unset($sectionLink);
$arResult['_STRUCTURED_SECTIONS'] = $arStructured;
?>