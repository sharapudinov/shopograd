<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? // Uses parent tag with class .main_menu ?>
<?foreach($arResult as $k=>$arItem):?>
    <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
<?endforeach?>
<div class="selector" title="ещё"><select></select></div>