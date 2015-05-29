<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="dropdowns">
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
        ?>
        <div id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <a href="#" class="dropdown_toggle" rel="dropdown_<?=$arItem['ID']?>"><?=$arItem['NAME']?></a>
            <div class="dropdown_content" id="dropdown_<?=$arItem['ID']?>">
                <h2><?=$arItem['NAME']?></h2>
                <?=$arItem['PREVIEW_TEXT'];?>
            </div>
        </div>
        <div class="gap20"></div>
    <?endforeach;?>
</div>