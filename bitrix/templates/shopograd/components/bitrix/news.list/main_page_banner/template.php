<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="main_banner" id="main_banner">
    <div class="pager">
    	<?$counter=0;?>
    	<?foreach($arResult["ITEMS"] as $arItem):?>
        	<?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
            ?>
            <a data-slide-index="<?=$counter?>" href="" id="<?=$this->GetEditAreaId($arItem['ID']);?>"></a>
            <?$counter++;?>
        <?endforeach;?>
    </div>
    <style>
		<?$counter=0;?>
    	<?foreach($arResult["ITEMS"] as $arItem):?>
			<?if($arItem['DISPLAY_PROPERTIES']['MOBILE_PICTURE']['FILE_VALUE']['SRC']):?>
				#main_banner_slide_<?=$counter?> {
					background-image:url(<?if($arItem['DISPLAY_PROPERTIES']['MOBILE_PICTURE']['FILE_VALUE']['WIDTH']<1000):?><?=$arItem['DISPLAY_PROPERTIES']['MOBILE_PICTURE']['FILE_VALUE']['SRC']?><?else:?><?=phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $arItem['DISPLAY_PROPERTIES']['MOBILE_PICTURE']['FILE_VALUE']['SRC'] . '&w=1000&f=jpg&q=85');?><?endif;?>);	
				}
				@media only screen and (min-width: 1025px) {
			<?endif;?>
				#main_banner_slide_<?=$counter?> {
					background-image:url(<?if($arItem["PREVIEW_PICTURE"]['WIDTH']<1500):?><?=$arItem["PREVIEW_PICTURE"]['SRC']?><?else:?><?=phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $arItem["PREVIEW_PICTURE"]['SRC'] . '&w=1500&f=jpg&q=85');?><?endif;?>);	
				}	
			<?if($arItem['DISPLAY_PROPERTIES']['MOBILE_PICTURE']['FILE_VALUE']['SRC']):?>
				}
			<?endif;?>
			<?$counter++;?>
		<?endforeach;?>
    </style>
    <div class="slider">
    	<?$counter=0;?>
    	<?foreach($arResult["ITEMS"] as $arItem):?>
            <div>
                <<?if($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']):?>a href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>"<?else:?>div<?endif;?> class="slide" id="main_banner_slide_<?=$counter?>"></<?if($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']):?>a<?else:?>div<?endif;?>>
            </div>
            <?$counter++;?>
        <?endforeach;?>
    </div>
</div>