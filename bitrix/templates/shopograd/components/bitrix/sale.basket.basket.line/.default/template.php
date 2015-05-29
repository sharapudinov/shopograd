<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?if($APPLICATION->GetCurDir() != $arParams['PATH_TO_BASKET']):?>
<a class="cart bold<?if(count($arResult['CATEGORIES']['READY'])==0):?> empty<?endif;?>" id="cart" href="<?=$arParams['PATH_TO_BASKET'];?>">
    <img src="<?=$templateFolder;?>/img/icon.png" />
    &nbsp;
    Мой заказ
    <span><?if(count($arResult['CATEGORIES']['READY'])>0):?><?=count($arResult['CATEGORIES']['READY']);?><?else:?>0<?endif;?></span>
</a>
<?endif;?>