<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
CModule::IncludeModule('iblock');
if (count($arResult["SECTIONS"])):
	?>
	<div class="catalogue_sections<?if($arParams['_LESS_COLUMNS']):?> less_columns<?endif;?>">
    	<?if($arParams['_VIEW_IMAGES']):?>
        	<div class="inner">
        <?endif;?>
		<?
		foreach($arResult["SECTIONS"] as $arSection):
		?>
			<?
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => "Really?"));
			?>
            <?if(!$arParams['_VIEW_IMAGES']):?>
				<a href="<?=$arSection['SECTION_PAGE_URL']?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>" class="inline_section"><?=$arSection['NAME']?><?if($arSection['_COUNTER']):?>  <span>(<?=$arSection['_COUNTER']?>)</span><?endif;?></a>
            <?else:?>
            	<?
				$img = false;
				$img = CFile::GetPath($arSection['DETAIL_PICTURE']);
				if(!$img) {
					$arRandomProduct = CIBlockElement::GetList (
					   array("RAND" => "ASC"),
					   array("IBLOCK_ID" => $arParams['IBLOCK_ID'], 'SECTION_ID'=>$arSection['ID'],"ACTIVE"=>"Y",'ACTIVE_DATE'=>"Y",'!PREVIEW_PICTURE'=>false,'SECTION_ACTIVE'=>"Y","SECTION_GLOBAL_ACTIVE"=>"Y","INCLUDE_SUBSECTIONS"=>"Y"),
					   false,
					   array("nTopCount" => 1),
					   array('ID','IBLOCK_ID','PREVIEW_PICTURE')
					)->GetNext();
					$img = CFile::GetPath($arRandomProduct['PREVIEW_PICTURE']);
				}
				?>
            	<div class="item_wrapper">
                	<a href="<?=$arSection['SECTION_PAGE_URL']?>" class="item<?if($arSection['UF_NOT_CROP_IMG']):?> not_crop_img<?endif;?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
                    	<div class="image" <?if($img):?>style="background-image:url(<?if($arSection['UF_NOT_CROP_IMG']):?><?=phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $img . '&w=400&f=png');?><?else:?><?=phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $img . '&w=360&h=400&zc=1&f=jpg&q=85');?><?endif;?>);"<?endif;?>>
                            <img src="<?=$templateFolder;?>/img/px.gif" />
                        </div>
                        <div class="descr">
							<div class="name"><?=$arSection['NAME']?><?if($arSection['_COUNTER']):?> <span>(<?=$arSection['_COUNTER']?>)</span><?endif;?></div>
                        </div>
                    </a>
                </div>
            <?endif;?>
		<?endforeach;?>
        <?if($arParams['_VIEW_IMAGES']):?>
        		<div class="clear"></div>
        	</div>
        <?endif;?>
	</div>
    <?if(!$arParams['_VIEW_IMAGES']):?>
		<div class="gap20"></div><div class="gap5"></div><div class="gap5"></div>
    <?endif;?>
	<?
else:
	?>
    <div class="catalogue_filter" id="catalogue_filter">
    	<form action="<?=$APPLICATION->GetCurPage();?>">
        <?if($arParams['_GLOBAL_SEARCH_TYPE']):?>
        <input type="hidden" name="GLOBAL_SEARCH_TYPE" value="<?=$arParams['_GLOBAL_SEARCH_TYPE']?>" />
        <?endif;?>
        <?if($arParams['_GLOBAL_SEARCH_CONDITION']):?>
        <input type="hidden" name="GLOBAL_SEARCH_CONDITION" value="<?=$arParams['_GLOBAL_SEARCH_CONDITION']?>" />
        <?endif;?>
        <div class="row collapse">
            <div class="small-12 medium-12 large-7 columns">
                    <div class="item">
                    	<?
						CModule::IncludeModule("currency");
						$filter_set = false;
						$rsEnum = CUserFieldEnum::GetList(array(), array("ID"=>$arResult['SECTION']['UF_CURRENCY'])); 
						$arEnum = $rsEnum->GetNext();
						$section_currency = $arEnum["VALUE"];
						$min_price = floor(CCurrencyRates::ConvertCurrency($arResult['SECTION']['UF_MIN_PRICE'], $section_currency, $arParams['_CURRENCY']));
						$max_price = ceil(CCurrencyRates::ConvertCurrency($arResult['SECTION']['UF_MAX_PRICE'], $section_currency, $arParams['_CURRENCY']));
						if($arParams['_FILTER_AND_SORTING']['MIN_PRICE']) {
							$min_price = $arParams['_FILTER_AND_SORTING']['MIN_PRICE'];
							if($min_price!=floor(CCurrencyRates::ConvertCurrency($arResult['SECTION']['UF_MIN_PRICE'], $section_currency, $arParams['_CURRENCY']))) {
								$filter_set = true;
							}
						}
						if($arParams['_FILTER_AND_SORTING']['MAX_PRICE']) {
							$max_price = $arParams['_FILTER_AND_SORTING']['MAX_PRICE'];
							if($max_price!=ceil(CCurrencyRates::ConvertCurrency($arResult['SECTION']['UF_MAX_PRICE'], $section_currency, $arParams['_CURRENCY']))) {
								$filter_set = true;
							}	
						}
						?>
                    	<input type="hidden" name="SECTION_CURRENCY" value="<?=$section_currency;?>" />
                        Цена от <span class="inline_input"><span><?=$min_price?></span><input type="text" value="<?=$min_price;?>" name="MIN_PRICE" /></span> до <span class="inline_input"><span><?=$max_price;?></span><input type="text" value="<?=$max_price;?>" name="MAX_PRICE" /></span> <?if($arParams['_CURRENCY']=='RUB'):?><span class="rub_sign"><span class="hyphen"></span><span class="hyphen2"></span><span class="ruble">p</span><span class="dot">уб.</span></span><?elseif($arParams['_CURRENCY'] == 'USD'):?>$<?else:?><?=$arParams['_CURRENCY']?><?endif;?>
                    </div>
                    <?for($i=1;$i<=5;$i++):?>
                    	<?if(strlen($arResult['SECTION']['UF_FILTER_FIELD_' . $i])):?>
                        	<?
							$any_text = 'любой';
							if(strlen($arResult['SECTION']['UF_FILTER_FIELD_' . $i . '_A'])) {
								$any_text = $arResult['SECTION']['UF_FILTER_FIELD_' . $i . '_A'];	
							}
							$current_val_text = $any_text;
							if($arParams['_FILTER_AND_SORTING']['FILTER_FIELD_' . $i]) {
								$current_val_text = $arParams['_FILTER_AND_SORTING']['FILTER_FIELD_' . $i];
								$filter_set = true;
							}
							?>
                            <div class="item">
                                <?=$arResult['SECTION']['UF_FILTER_FIELD_' . $i]?>
                                <span class="inline_select">
                                    <span><?=$current_val_text?></span> <img src="<?=$templateFolder;?>/img/arr.png" />
                                    <select name="FILTER_FIELD_<?=$i?>">
                                        <option value="" data-text="<?=$any_text?>"<?if(!$arParams['_FILTER_AND_SORTING']['FILTER_FIELD_' . $i]):?> selected<?endif;?>>---<?=$any_text?>---</option>
                                        <?foreach($arResult['SECTION']['UF_FILTER_FIELD_' . $i . '_V'] as $v):?>
                                        <option value="<?=$v?>" data-text="<?=$v?>"<?if($arParams['_FILTER_AND_SORTING']['FILTER_FIELD_' . $i]==$v):?> selected<?endif;?>><?=$v?></option>
                                        <?endforeach;?>
                                    </select>
                                </span>
                            </div>
                    	<?endif;?>
                    <?endfor;?>
                    <?if($filter_set):?>
                    	<div class="item">
                    		<a href="<?=$APPLICATION->GetCurPageParam('',array('SECTION_CURRENCY','MIN_PRICE','MAX_PRICE','FILTER_FIELD_1','FILTER_FIELD_2','FILTER_FIELD_3','FILTER_FIELD_4','FILTER_FIELD_5'));?>">показать все</a>
                        </div>
                    <?endif;?>
            </div>
            <div class="small-12 medium-12 columns show-for-medium-down">
                <hr />
            </div>
            <div class="small-12 medium-12 large-5 columns text-right">
                Упорядочить по
                <span class="inline_select">
                    <span><?=$arParams['_FILTER_AND_SORTING']['ALL_SORT_TYPES'][$arParams['_FILTER_AND_SORTING']['SORT_TYPE']]?></span> <img src="<?=$templateFolder;?>/img/arr.png" />
                    <select name="SORT">
                    	<?foreach($arParams['_FILTER_AND_SORTING']['ALL_SORT_TYPES'] as $k=>$v):?>
                        <option value="<?=$k?>" data-text="<?=$v?>"<?if($k == $arParams['_FILTER_AND_SORTING']['SORT_TYPE']):?> selected<?endif;?>><?=$v?></option>
                        <?endforeach;?>
                    </select>
                </span>
                по
                <span class="inline_select">
                    <span><?=$arParams['_FILTER_AND_SORTING']['ALL_ORDER_TYPES'][$arParams['_FILTER_AND_SORTING']['ORDER_TYPE']]?></span> <img src="<?=$templateFolder;?>/img/arr.png" />
                    <select name="ORDER">
                        <?foreach($arParams['_FILTER_AND_SORTING']['ALL_ORDER_TYPES'] as $k=>$v):?>
                        <option value="<?=$k?>" data-text="<?=$v?>"<?if($k == $arParams['_FILTER_AND_SORTING']['ORDER_TYPE']):?> selected<?endif;?>><?=$v?></option>
                        <?endforeach;?>
                    </select>
                </span>
            </div>
        </div>
        </form>
    </div>
    <div class="gap40"></div>
	<?
endif;
?>