<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>
<?ShowError($arResult["strProfileError"]);?>
<?
if ($arResult['DATA_SAVED'] == 'Y') {
	ShowNote("Изменения сохранены!");
}
?>

<form method="post" action="<?=$arResult["FORM_TARGET"]?>" class="custom">
	<?=$arResult["BX_SESSION_CHECK"]?>
    <input type="hidden" name="lang" value="<?=LANG?>" />
    <input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
	<p>Последнее изменение: <?=$arResult["arUser"]["TIMESTAMP_X"]?></p>
    <div class="gap5"></div><div class="gap5"></div>
    <div class="row_negative_margins">
    	<div class="row">
            <div class="large-4 medium-6 small-12 columns">
                <input type="text" placeholder="Имя" name="NAME" value="<?=$arResult["arUser"]["NAME"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-6 small-12 columns">
                <input type="text" placeholder="Фамилия" name="LAST_NAME" value="<?=$arResult["arUser"]["LAST_NAME"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-6 small-12 columns">
                <input type="text" placeholder="Эл. почта" name="EMAIL" value="<?=$arResult["arUser"]["EMAIL"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-6 small-12 columns">
                <input type="text" placeholder="Логин*" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"]?>" />
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-6 small-12 columns">
                <input type="password" placeholder="Новый пароль**" name="NEW_PASSWORD" />
                <div class="gap20"></div>
            </div>
            <div class="large-4 medium-6 small-12 columns">
                <input type="password" placeholder="Повтор пароля" name="NEW_PASSWORD_CONFIRM" />
                <div class="gap20"></div>
            </div>
        </div>
	</div>
    <input type="submit" value="Сохранить" name="save" />
    <div class="gap20"></div>
    <p>* Логин &mdash; минимум 3 символа.</p>
	<p>** Если пароль изменять не требуется &mdash; оставьте поле пустым. <?if(strlen($arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"])):?><?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?><?endif;?></p>
</form>
<?
if($arResult["SOCSERV_ENABLED"])
{
	?>
    <div class="gap20"></div>
    <?
	$APPLICATION->IncludeComponent("bitrix:socserv.auth.split", ".default", array(
			"SHOW_PROFILES" => "Y",
			"ALLOW_DELETE" => "Y"
		),
		false
	);
}
?>