<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="top_sections">
    <ul class="small-block-grid-2 medium-block-grid-1 large-block-grid-1">
    	<?
		foreach($arResult["SECTIONS"] as $arSection):
			?>
            <?
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => "Really?"));
			?>
			<li>
				<a href="<?=$arSection['SECTION_PAGE_URL']?>" style="background-image:url(<?if($arSection['PICTURE']['SRC']):?><?=$arSection['PICTURE']['SRC']?><?else:?><?=CFile::GetPath($arSection['PICTURE']);?><?endif;?>);" id="<?=$this->GetEditAreaId($arSection['ID']);?>"><?=$arSection['NAME']?></a>
			</li>
			<?
        endforeach;
        ?>
        <?
		$ob = CIBlockElement::GetList(false, array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'SECTION_ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y'), array('IBLOCK_ID'));
		$el = $ob->Fetch();
		$total_elements = intval($el['CNT']);
		// rich russian language...
		$num_products_lang_transform = "товаров";
		if (!(($total_elements>10) and ($total_elements<20))) {
			preg_match('/\d$/', $total_elements, $matches);
			if ($matches[0] == 1) {
				$num_products_lang_transform = "товар";
			} else {
				if (($matches[0]>1) and ($matches[0]<5)) {
					$num_products_lang_transform = "товара";
				}
			}
		}
		?>
        <li>
            <a href="/katalog/" class="showall">И еще <!--<?=$total_elements?> <?=$num_products_lang_transform?>--> тысячи товаров!</a>
        </li>
    </ul>
</div>