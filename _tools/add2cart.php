<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

$ID = intval(trim($_REQUEST['id']));

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

$arElementMainFields = CIBlockElement::GetByID($ID)->GetNext();

if($arElementMainFields['ID'] and in_array($arElementMainFields['IBLOCK_ID'],array(1,2))) {
	$arElementFields = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arElementMainFields['IBLOCK_ID'], 'ID' => $arElementMainFields['ID']), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_CODE', 'PROPERTY_CML2_LINK.PROPERTY_CODE', 'PROPERTY_ORIGINAL_LINK', 'PROPERTY_CML2_LINK.PROPERTY_ORIGINAL_LINK'))->GetNext();
	$product_code = '';
	if($arElementFields['PROPERTY_CODE_VALUE']) {
		$product_code = $arElementFields['PROPERTY_CODE_VALUE'];
	} elseif ($arElementFields['PROPERTY_CML2_LINK_PROPERTY_CODE_VALUE']) {
		$product_code = $arElementFields['PROPERTY_CML2_LINK_PROPERTY_CODE_VALUE'];	
	}
	$original_link = '';
	if($arElementFields['PROPERTY_ORIGINAL_LINK_VALUE']) {
		$original_link = $arElementFields['PROPERTY_ORIGINAL_LINK_VALUE'];
	} elseif ($arElementFields['PROPERTY_CML2_LINK_PROPERTY_ORIGINAL_LINK_VALUE']) {
		$original_link = $arElementFields['PROPERTY_CML2_LINK_PROPERTY_ORIGINAL_LINK_VALUE'];	
	}
	Add2BasketByProductID($arElementMainFields['ID'], 1, array(array('NAME'=>"Артикул",'CODE'=>"CODE",'VALUE'=>$product_code),array('NAME'=>"Ссылка на первоисточник",'ORIGINAL_LINK'=>"CODE",'VALUE'=>$original_link)));
	LocalRedirect("/katalog/oformlenie-zakaza/?backurl=".$_REQUEST['backurl']);
} else {
	die("Don't try to cheat me :)");	
}
?>