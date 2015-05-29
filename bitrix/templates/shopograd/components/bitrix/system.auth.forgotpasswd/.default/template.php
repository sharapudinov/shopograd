<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddChainItem("Pwd recovery");
?>
<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" class="custom">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
    <?if(!strlen($arParams["~AUTH_RESULT"]['MESSAGE'])):?>
	<p>
		Если Вы забыли пароль &mdash; введите логин или e-mail. Инструкции по смене пароля будут высланы Вам на электронную почту, указанную при регистрации. 
	</p>
    <?endif;?>
    
    <div class="row_negative_margins">
    	<div class="row">
            <div class="large-4 medium-5 small-5 columns">
                <input type="text" placeholder="Логин" name="USER_LOGIN" value="<?=$arResult["LAST_LOGIN"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-1 medium-2 small-2 columns text-center">
            	<div class="gap5"></div><div class="gap5"></div>
                или
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-5 small-5 columns">
                <input type="text" placeholder="Эл. почта" name="USER_EMAIL" />
                <div class="gap20"></div>
            </div>
            <div class="large-3 medium-12 small-12 columns">
                <input type="submit" value="Выслать" name="send_account_info" />
                <div class="gap20"></div>
            </div>
        </div>
    </div>
    <p>
    Если Вы вдруг вспомнили пароль &mdash; незамедлительно пройдите <a href="<?=$arResult["AUTH_AUTH_URL"]?>">сюда</a>.
    </p> 
</form>