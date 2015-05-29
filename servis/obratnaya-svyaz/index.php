<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обратная связь");
?>
<?if($_REQUEST['formresult']=="addok"):?>
<p style="color:green">Спасибо! Ваш запрос принят! Ожидайте ответа в ближайшее время!</p>
<?endif;?>
<?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"simple", 
	array(
		"SEF_MODE" => "Y",
		"WEB_FORM_ID" => "1",
		"LIST_URL" => "",
		"EDIT_URL" => "",
		"SUCCESS_URL" => "",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "Y",
		"USE_EXTENDED_ERRORS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "604800",
		"SEF_FOLDER" => "/servis/obratnaya-svyaz/"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>