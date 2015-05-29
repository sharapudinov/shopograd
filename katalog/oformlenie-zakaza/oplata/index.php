<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оплата");
?>
<?
CModule::IncludeModule("sale");
$arOrder = CSaleOrder::GetByID(intval(trim($_REQUEST['ORDER_ID'])));
if ($arOrder['ID']) {
	if($arOrder['PAYED'] == 'Y') {
		?>
		<p><b>Заказ №<?=$arOrder['ID']?></b> уже оплачен. Спасибо!</p>
		<?
	} else {
		?>
		<p><b>Заказ №<?=$arOrder['ID']?></b> еще не оплачен. Нажмите кнопку для начала процедуры оплаты заказа.</p>
		<p><a href="<?=pay_url($arOrder['ID'],floatval($arOrder['PRICE']))?>" class="button">Оплатить заказ</a></p>
		<p>После нажатия кнопки Вы будете переадресованы на сайт платежной системы.</p>
		<?
	}
} else {
	LocalRedirect("/katalog/");
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>