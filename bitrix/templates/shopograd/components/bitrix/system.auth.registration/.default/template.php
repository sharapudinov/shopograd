<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddChainItem("Registration");
?>
<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" class="custom">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="REGISTRATION" />

	<div class="row_negative_margins">
    	<div class="row">
            <div class="large-4 medium-6 small-12 columns">
                <input type="text" placeholder="Имя" name="USER_NAME" value="<?=$arResult["USER_NAME"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-6 small-12 columns">
                <input type="text" placeholder="Фамилия" name="USER_LAST_NAME" value="<?=$arResult["USER_LAST_NAME"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-6 small-12 columns">
                <input type="text" placeholder="Эл. почта" name="USER_EMAIL" value="<?=$arResult["USER_EMAIL"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-6 small-12 columns">
                <input type="text" placeholder="Придумайте логин*" name="USER_LOGIN" value="<?=$arResult["USER_LOGIN"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-6 small-12 columns">
                <input type="password" placeholder="Пароль<?if(strlen($arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"])):?>**<?endif;?>" name="USER_PASSWORD" value="<?=$arResult["USER_PASSWORD"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-6 small-12 columns">
                <input type="password" placeholder="Повтор пароля" name="USER_CONFIRM_PASSWORD" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" />
                <div class="gap20"></div>
            </div>
        </div>
    <?if($arResult["USE_CAPTCHA"] == "Y"):?>
        <div class="row">
            <div class="large-4 medium-6 small-6 columns">
                <input type="text" placeholder="Цифры с картинки" name="captcha_word" />
                <div class="gap20"></div>
            </div>
            <div class="large-8 medium-6 small-6 columns">
                <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                <img src="<?=$templateFolder;?>/img/arr_left.png" />
                &nbsp;
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" style="height:35px;" />
                <div class="gap20"></div>
            </div>
        </div>
    <?endif;?>
    </div>
    <input type="submit" value="Зарегистрироваться" name="Register" />
    <div class="gap20"></div>
    <p>* Логин &mdash; минимум 3 символа.</p>
	<?if(strlen($arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"])):?>
    	<p>** <?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
    <?endif;?>
	<p>
    Если Вы вдруг вспомнили, что уже зарегистрированы &mdash; незамедлительно пройдите <a href="<?=$arResult["AUTH_AUTH_URL"]?>">сюда</a>.
    </p>
</form>