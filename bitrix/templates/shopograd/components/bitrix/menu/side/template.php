<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="show-for-large-up">
    <div class="side_menu">
    	<?foreach($arResult as $k=>$arItem):?>
        <a href="<?=$arItem["LINK"]?>"<?if($arItem["SELECTED"]):?> class="current"<?endif;?>><?=$arItem["TEXT"]?></a>
        <?endforeach;?>
    </div>
</div>
<div class="hide-for-large-up">
    <select class="custom" id="side_menu">
        <option value="">— <?if($arParams['_MOBILE_TITLE']):?><?=$arParams['_MOBILE_TITLE']?><?else:?>Меню<?endif;?> —</option>
        <?foreach($arResult as $k=>$arItem):?>
        <option value="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></option>
        <?endforeach;?>
    </select>
</div>