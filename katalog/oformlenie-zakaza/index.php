<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");
?>
<?if(!$_REQUEST['ORDER_ID']):?>
<?if($_REQUEST['backurl']):?>
<div class="hint">
	<div class="gap5"></div><div class="gap5"></div>
	Товар успешно добавлен в корзину! &nbsp; <a href="<?=urldecode($_REQUEST['backurl']);?>" class="return_to_shop" id="return_to_shop_2" style="display:none;">продолжить покупки</a>
	<div  id="return_to_shop_1">
		<div class="gap5"></div><div class="gap5"></div>
		<a href="<?=urldecode($_REQUEST['backurl']);?>" class="button small_button return_to_shop">Продолжить покупки</a>
	</div>
	<div class="gap5"></div><div class="gap5"></div>
</div>
<div class="gap20"></div><div class="gap20"></div>
<script>
	if($.cookie('cartbackurl')) {
		$('.return_to_shop').attr('href', $.cookie('cartbackurl'));
	}
	setTimeout('$("#return_to_shop_1").fadeOut("fast"); $("#return_to_shop_2").fadeIn("fast"); /*$("#return_to_shop").removeClass("button"); $("#return_to_shop").removeClass("small_button");*/',5000);
</script>
<?endif;?>
<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket", ".default", Array(
	"COLUMNS_LIST" => array(	// Выводимые колонки
		0 => "NAME",
		1 => "QUANTITY",
		2 => "DELETE",
		3 => "PRICE",
	),
	"PATH_TO_ORDER" => "/katalog/oformlenie-zakaza/",	// Страница оформления заказа
	"HIDE_COUPON" => "N",	// Спрятать поле ввода купона
	"PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
	"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",	// Рассчитывать скидку для каждой позиции (на все количество товара)
	"USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
	"QUANTITY_FLOAT" => "N",	// Использовать дробное значение количества
	"SET_TITLE" => "N",	// Устанавливать заголовок страницы
	),
	false
);?>
<?endif;?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax",
	".default",
	array(
		"PAY_FROM_ACCOUNT" => "N",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"COUNT_DELIVERY_TAX" => "N",
		"ALLOW_AUTO_REGISTER" => "Y",
		"SEND_NEW_USER_NOTIFY" => "Y",
		"DELIVERY_NO_AJAX" => "Y",
		"DELIVERY_NO_SESSION" => "Y",
		"TEMPLATE_LOCATION" => "popup",
		"DELIVERY_TO_PAYSYSTEM" => "d2p",
		"USE_PREPAYMENT" => "N",
		"PROP_1" => array(
		),
		"PATH_TO_BASKET" => "/katalog/oformlenie-zakaza/",
		"PATH_TO_PERSONAL" => "",
		"PATH_TO_PAYMENT" => "/katalog/oformlenie-zakaza/oplata/",
		"PATH_TO_AUTH" => "",
		"SET_TITLE" => "N",
		"PRODUCT_COLUMNS" => array(
		),
		"DISABLE_BASKET_REDIRECT" => "Y"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>