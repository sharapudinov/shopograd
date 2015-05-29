<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="section_navigation_icons">
    <ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-4 text-center">
    <?foreach($arResult["ITEMS"] as $arElement):?>
        <?
        $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => 'Really?'));
        ?>
        <li id="<?=$this->GetEditAreaId($arElement['ID']);?>">
            <a href="<?=$arElement['DISPLAY_PROPERTIES']['LINK']['VALUE']?>">
                <img src="<?=$arElement['PREVIEW_PICTURE']['SRC']?>" />
                <div class="gap20"></div>
                <?=$arElement['NAME']?>
            </a>
        </li>
    <?endforeach;?>
    </ul>
</div>