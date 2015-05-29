<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["PAY_SYSTEM"]) == 1):?>
<input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arResult["PAY_SYSTEM"][0]['ID'];?>" />
<?else:?>
<?
	foreach($arResult["PAY_SYSTEM"] as $arPaySystem)
	{
		?>
        <label class="inline_label">
            <input
            type="radio"
            name="PAY_SYSTEM_ID"
            value="<?=$arPaySystem['ID'];?>"
            <?=$arPaySystem["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?>
            onclick="submitForm();"
            />
            <?=htmlspecialcharsbx($arPaySystem["NAME"]);?>
        </label>
        <?
	}
?>
<?endif;?>