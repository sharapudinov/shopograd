<?
function print_price($val) {
	return preg_replace('/RUB/i','<span class="rub_sign"><span class="hyphen"></span><span class="hyphen2"></span><span class="ruble">p</span><span class="dot">уб.</span></span>', $val);	
}
function pay_url($order,$sum) {
	return "https://paymentgateway.ru/pgw/?project=8444&amount=" . floatval($sum) . "&nickname=" . intval($order) . "&comment=" . urlencode('Оплата заказа №' . $order . ' в интернет-магазине ШОПОГРАД (www.shopograd.ru)');
}
?>