<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddChainItem("Pwd change");
?>
<?
ShowMessage($arParams["~AUTH_RESULT"]);
?>
<form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform" class="custom">
	<?if (strlen($arResult["BACKURL"]) > 0): ?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<? endif ?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="CHANGE_PWD">
    
    <div class="row_negative_margins">
    	<div class="row">
            <div class="large-3 medium-6 small-12 columns">
                <input type="text" placeholder="Логин" name="USER_LOGIN" value="<?=$arResult["LAST_LOGIN"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-3 medium-6 small-12 columns">
                <input type="text" placeholder="Контрольная строка" name="USER_CHECKWORD" value="<?=$arResult["USER_CHECKWORD"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-3 medium-6 small-12 columns">
                <input type="password" placeholder="Новый пароль<?if(strlen($arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"])):?>*<?endif;?>" name="USER_PASSWORD" value="<?=$arResult["USER_PASSWORD"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-3 medium-6 small-12 columns">
                <input type="password" placeholder="Повтор пароля" name="USER_CONFIRM_PASSWORD" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" />
                <div class="gap20"></div>
            </div>
        </div>
    </div>
    <input type="submit" value="Сменить пароль" name="change_pwd" />
    <div class="gap20"></div>
    
    <?if(strlen($arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"])):?>
    	<p>* <?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
    <?endif;?>
    <p>
    Если Вы вдруг вспомнили пароль &mdash; незамедлительно пройдите <a href="<?=$arResult["AUTH_AUTH_URL"]?>">сюда</a>.
    </p> 
</form>
