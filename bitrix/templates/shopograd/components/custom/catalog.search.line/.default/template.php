<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="search_line" id="search_line">
	<form action="/katalog/" method="get">
        <input type="hidden" name="GLOBAL_SEARCH_TYPE" value="1" />
        <div class="row collapse">
            <div class="large-7 medium-4 small-8 columns">
                <div class="search_frame_left">
                <input type="text" name="GLOBAL_SEARCH_CONDITION" placeholder="Поиск товара..." class="search_query" />
                </div>
            </div>
            <div class="large-3 medium-4 hide-for-small columns">
                <div class="search_frame_middle">
                    <div class="section_selector">
                        <span>Все категории</span>
                        <div class="fade"></div>
                        <div class="arr"></div>
                        <select>
                            <option value='/katalog/' data-text='Все категории'>--- Все категории ---</option>
                            <?
                            foreach($arResult["SECTIONS"] as $arSection):
								?>
								<option value='<?=$arSection['SECTION_PAGE_URL']?>' data-text='<?=$arSection['NAME']?>'><?=$arSection['NAME']?></option>
								<?
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="large-2 medium-4 small-4 columns">
                <input type="submit" class="search_submit bold" value="Искать" />
            </div>
        </div>
    </form>
</div>