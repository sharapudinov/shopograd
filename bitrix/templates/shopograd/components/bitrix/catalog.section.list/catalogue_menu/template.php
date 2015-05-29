<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="catalogue_menu">
    <ul class="small-block-grid-3 large-block-grid-4">
    	<?
		foreach($arResult['_STRUCTURED_SECTIONS']['ROOT']['CHILD'] as $arSection):
			?>	
        	<?
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => "Really?"));
			?>
            <li>
                <a href="<?=$arSection['SECTION_PAGE_URL']?>" class="item_icon" style="background-image:url(<?=$arSection['PICTURE']['SRC']?>);"></a>
                <div class="item_content">
                    <a href="<?=$arSection['SECTION_PAGE_URL']?>" class="item_name" id="<?=$this->GetEditAreaId($arSection['ID']);?>"><?=$arSection['NAME']?></a>
                    <div class="gap5"></div>
                    <div class="subsections">
                    	<?
						$childs_counter = 0;
						?>
                        <?foreach($arSection['CHILD'] as $arChild):?>
                        	<?
							$childs_counter++;
							?>
                            <?if($childs_counter<=5):?>
								<?
                                $this->AddEditAction($arChild['ID'], $arChild['EDIT_LINK'], CIBlock::GetArrayByID($arChild["IBLOCK_ID"], "SECTION_EDIT"));
                                $this->AddDeleteAction($arChild['ID'], $arChild['DELETE_LINK'], CIBlock::GetArrayByID($arChild["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => "Really?"));
                                ?>
                                <a href="<?=$arChild['SECTION_PAGE_URL']?>" id="<?=$this->GetEditAreaId($arChild['ID']);?>"><?=$arChild['NAME']?></a>&nbsp;&nbsp;&nbsp; 
                            <?endif;?>
                        <?endforeach;?>
                        <a href="<?=$arSection['SECTION_PAGE_URL']?>">все товары</a>
                    </div>
                </div>
                <div class="clear"></div>
            </li>
            <?
		endforeach;
		?>
    </ul>
</div>