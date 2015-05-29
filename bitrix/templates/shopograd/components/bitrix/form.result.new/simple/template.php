<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="webform_container custom_form">
    
    <?if ($arResult["isFormErrors"] == "Y"):?><p style="color:red"><?=$arResult["FORM_ERRORS_TEXT"];?></p><div class="gap20"></div><?endif;?>
    
    <?=$arResult["FORM_NOTE"]?>
    
    <?=$arResult["FORM_HEADER"]?>
    
    <?if($arResult["FORM_DESCRIPTION"]):?>
    <p><?=$arResult["FORM_DESCRIPTION"]?></p>
    <?endif;?>
        <?
        foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
        {
        ?>
            <p><?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"] == "Y"):?><?=$arResult["REQUIRED_SIGN"];?><?endif;?></p>
            <?=$arQuestion["HTML_CODE"]?>
            <div class="gap5"></div><div class="gap5"></div>
        <? 
        } //endwhile 
        ?>
    <?
    if($arResult["isUseCaptcha"] == "Y")
    {
    ?>
            <p>Введите цифры с картинки:</p>
            <input type="hidden" name="captcha_sid" value="<?=htmlspecialchars($arResult["CAPTCHACode"]);?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialchars($arResult["CAPTCHACode"]);?>" />
            <div class="gap5"></div>
            <input type="text" name="captcha_word" size="10" maxlength="5" value="" />
            <div class="gap5"></div><div class="gap5"></div>
    <?
    } // isUseCaptcha
    ?>
    <div class="gap5"></div><div class="gap5"></div>
    <input <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? "Отправить" : $arResult["arForm"]["BUTTON"];?>" />
    <div class="gap5"></div><div class="gap5"></div>
    <p>* &mdash; обязательные поля</p>
    <?=$arResult["FORM_FOOTER"]?>
</div> <!--.webform_container-->