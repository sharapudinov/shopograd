<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?foreach($arResult as $k=>$arItem):?>
    <li>
    	<a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
    </li>
<?endforeach?>