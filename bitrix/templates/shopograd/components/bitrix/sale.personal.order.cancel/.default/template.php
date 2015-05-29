<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$APPLICATION->AddChainItem("Order cancel");
?>
<?if(strlen($arResult["ERROR_MESSAGE"])<=0):?>
    <form method="post" action="<?=POST_FORM_ACTION_URI?>" class="custom">
        
        <input type="hidden" name="CANCEL" value="Y">
        <?=bitrix_sessid_post()?>
        <input type="hidden" name="ID" value="<?=$arResult["ID"]?>">
 
        <p>Пожалуйста, укажите причину отмены:</p>
        <textarea name="REASON_CANCELED" style="width:100%;"></textarea>
        
        <div class="gap20"></div>
        <p>Вы уверены, что хотите отменить заказ? Отмена заказа необратима!</p>
        <input type="submit" name="action" value="Да, отменить заказ">

    </form>
<?else:?>
    <?=ShowError($arResult["ERROR_MESSAGE"]);?>
<?endif;?>

<p><a href="<?=$arResult["URL_TO_LIST"]?>">вернуться в список заказов</a></p>