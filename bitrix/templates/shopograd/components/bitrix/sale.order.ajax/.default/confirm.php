<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!empty($arResult["ORDER"]))
{
?>
    <h2>Заказ №<?=$arResult["ORDER"]['ID']?> сформирован!</h2>
    <p>Вам на почту отправлено подтверждение заказа. Для начала обработки Вашего заказа требуется <b>предоплата</b>.</p>
	<p><a href="<?=pay_url($arResult["ORDER"]['ID'],floatval($arResult["ORDER"]['PRICE']))?>" class="button">Оплатить заказ</a></p>
	<p>После нажатия кнопки Вы будете переадресованы на сайт платежной системы.</p>
	<p>Вы можете совершить оплату позже, нажав соответствующую ссылку в <a href="/dlya-zhiteley/moi-zakazy/">личном кабинете</a>.</p>
<?
}
else
{
	?>
	<h2><?=GetMessage("ORDER_FAIL_1");?></h2>
    <p><?=GetMessage("ORDER_FAIL_2");?></p>
	<?
}
?>
<?
if ($USER->IsAuthorized()) $USER->SavePasswordHash();
?>