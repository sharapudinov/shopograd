<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="footer_menu">
    <div class="gap20"></div><div class="gap5"></div><div class="gap5"></div><div class="gap5"></div>
    <div class="centered_wrapper">
        <ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-5">
        	<?foreach($arResult as $k=>$arItem):?>
            	<?if($arItem['DEPTH_LEVEL']==1):?>
                    <?if($k!=0):?>
                    </li>
                    <?endif;?>
                    <li>
                        <a href="<?=$arItem["LINK"]?>" class="menu_section_title bold"><?=$arItem["TEXT"]?></a>
                        <div class="gap20"></div>
                <?else:?>
                	<a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                	<div class="gap5"></div><div class="gap5"></div>
                <?endif;?>
            <?endforeach;?>
            </li>
        </ul>
    </div>
    <div class="gap5"></div>
</div>