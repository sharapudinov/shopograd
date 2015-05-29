<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="pay_systems">
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
        ?>
        <?if(strlen($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'])):?>
        	<a href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>"<?if(preg_match('/^http/i',$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'])):?> target="_blank"<?endif;?>>
        <?endif;?>
        <?if($arItem["PREVIEW_PICTURE"]["HEIGHT"]!=35):?>
            <img src="<?=phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $arItem["PREVIEW_PICTURE"]["SRC"] . '&h=35&aoe=1&f=png');?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" />
        <?else:?>
            <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" />
        <?endif;?>
        <?if(strlen($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'])):?>
        	</a>
        <?endif;?>
    <?endforeach;?>
</div>