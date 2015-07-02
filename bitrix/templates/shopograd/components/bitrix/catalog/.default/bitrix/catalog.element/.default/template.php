<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div itemscope itemtype="http://schema.org/Product" class="product_detail" id="product_detail">
    <div class="product_main_part">
        <div class="offers">
            <? if ($arResult['_CURRENT_OFFER']['CAN_BUY']): ?>
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="offer current"
                     <? if ($arResult['_OFFERS_VIEW']): ?><? if ($arResult['_OFFERS_VIEW'] == 2): ?><? foreach ($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PARAMS']['VALUE'] as $k => $v): ?>data-<?= Cutil::translit(trim($v), 'ru'); ?>="<?= Cutil::translit(trim($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PARAMS']['DESCRIPTION'][$k]), 'ru'); ?>"
                     <? endforeach; ?><? else: ?>data-offer="<?= $arResult['_CURRENT_OFFER']['ID'] ?>"<? endif; ?><? endif; ?><? if ($arResult['_CURRENT_OFFER']['_IBLOCK_FIELDS']['DETAIL_PAGE_URL']): ?> data-ajax-url="<?= $arResult['_CURRENT_OFFER']['_IBLOCK_FIELDS']['DETAIL_PAGE_URL'] ?>"<? endif; ?>>
                    <a href="/_tools/add2cart.php?id=<?= $arResult['_CURRENT_OFFER']['ID']; ?>&backurl=<? if ($arResult['_CURRENT_OFFER']['_IBLOCK_FIELDS']['DETAIL_PAGE_URL']): ?><?= urlencode($arResult['_CURRENT_OFFER']['_IBLOCK_FIELDS']['DETAIL_PAGE_URL']); ?><? else: ?><?= urlencode($arResult['_CURRENT_OFFER']['DETAIL_PAGE_URL']); ?><? endif; ?><? //=$arResult['_CURRENT_OFFER']['BUY_URL']?>"
                       class="buy"></a>

                    <div class="price">
                        <div class="old_price">
                            <? if (intval($arResult['_CURRENT_OFFER']['_OLD_PRICE']) > 0): ?><?= intval($arResult['_CURRENT_OFFER']['_OLD_PRICE']) ?><? endif; ?>
                        </div>
                    <span itemprop="price" class="bold">
                        <?= $arResult['_CURRENT_OFFER']['_PRICE'] ?>
                        <meta itemprop="priceCurrency" content="<?=$arParams['CURRENCY_ID']?>">
                    </span>
                    </div>
                    <div class="clear"></div>
                    <div class="offer_params">
                        <? if ($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['CODE']['VALUE'] or $arResult['DISPLAY_PROPERTIES']['CODE']['VALUE']): ?>Артикул: <? if ($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['CODE']['VALUE']): ?><?= $arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['CODE']['VALUE'] ?><? else: ?><?= $arResult['DISPLAY_PROPERTIES']['CODE']['VALUE'] ?><? endif; ?>&nbsp;&nbsp;&nbsp;&nbsp; <? endif; ?>
                        <span><?= $arResult['_CURRENT_OFFER']['NAME'] ?></span>
                    </div>
                </div>
            <? endif; ?>
            <? if (count($arResult['OFFERS']) > 1): ?>
                <? foreach ($arResult['OFFERS'] as $arOffer): ?>
                    <? if ($arOffer['CAN_BUY'] and ($arOffer['ID'] != $arResult['_CURRENT_OFFER']['ID'])): ?>
                        <div  itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="offer"
                             <? if ($arResult['_OFFERS_VIEW'] == 2): ?><? foreach ($arOffer['DISPLAY_PROPERTIES']['PARAMS']['VALUE'] as $k => $v): ?>data-<?= Cutil::translit(trim($v), 'ru'); ?>="<?= Cutil::translit(trim($arOffer['DISPLAY_PROPERTIES']['PARAMS']['DESCRIPTION'][$k]), 'ru'); ?>"
                             <? endforeach; ?><? else: ?>data-offer="<?= $arOffer['ID'] ?>"<? endif; ?><? if ($arOffer['_IBLOCK_FIELDS']['DETAIL_PAGE_URL']): ?> data-ajax-url="<?= $arOffer['_IBLOCK_FIELDS']['DETAIL_PAGE_URL'] ?>"<? endif; ?>>
                            <a href="/_tools/add2cart.php?id=<?= $arOffer['ID']; ?>&backurl=<?= urlencode($arOffer['_IBLOCK_FIELDS']['DETAIL_PAGE_URL']); ?><? //=$arOffer['BUY_URL']?>"
                               class="buy"></a>

                            <div class="price">
                                <div class="old_price">
                                    <? if (intval($arOffer['_OLD_PRICE']) > 0): ?><?= $arOffer['_OLD_PRICE'] ?><? endif; ?>
                                </div>
                                <span itemprop="price" class="bold">
                                    <?= $arOffer['_PRICE'] ?>
                                    <meta itemprop="priceCurrency" content="<?= $arParams['CURRENCY_ID']?>">
                                </span>
                            </div>
                            <div class="clear"></div>
                            <div class="offer_params">
                                <? if ($arOffer['DISPLAY_PROPERTIES']['CODE']['VALUE'] or $arResult['DISPLAY_PROPERTIES']['CODE']['VALUE']): ?>Артикул: <? if ($arOffer['DISPLAY_PROPERTIES']['CODE']['VALUE']): ?><?= $arOffer['DISPLAY_PROPERTIES']['CODE']['VALUE'] ?><? else: ?><?= $arResult['DISPLAY_PROPERTIES']['CODE']['VALUE'] ?><? endif; ?>&nbsp;&nbsp;&nbsp;&nbsp; <? endif; ?>
                                <span><?= $arOffer['NAME'] ?></span>
                            </div>
                        </div>
                    <? endif; ?>
                <? endforeach; ?>
            <? endif; ?>
            <div class="offer_not_available <? if (!$arResult['_CURRENT_OFFER']['CAN_BUY']): ?> current<? endif; ?>">
                <div class="inner bold">
                    Нет в наличии :(
                </div>
            </div>
        </div>
        <div class="liquid_gap_1"></div>
        <div class="benefits">
            <?
            $num_columns = 2;
            if ($arResult['_FREE_DELIVERY'])
            {
                $num_columns++;
            }
            if ($arResult['DISPLAY_PROPERTIES']['AUTO_CALCULATED_REVIEWS_AMOUNT']['VALUE'])
            {
                $num_columns++;
            }
            ?>
            <ul class="small-block-grid-<?= $num_columns ?>">
                <li>
                    <img src="<?= $templateFolder ?>/img/icon_cert.png" width="32" height="32"/>

                    <div>Проверенный<br/>поставщик</div>
                </li>
                <li>
                    <img src="<?= $templateFolder ?>/img/icon_money.png" width="32" height="32"/>

                    <div>Гарантия<br/>возврата денег</div>
                </li>
                <? if ($arResult['_FREE_DELIVERY']): ?>
                    <li>
                        <img src="<?= $templateFolder ?>/img/icon_delivery.png" width="32" height="32"/>

                        <div>Бесплатная<br/>доставка</div>
                    </li>
                <? endif; ?>
                <? if ($arResult['DISPLAY_PROPERTIES']['AUTO_CALCULATED_REVIEWS_AMOUNT']['VALUE']): ?>
                    <li>
                        <img src="<?= $templateFolder ?>/img/icon_reviews.png" width="32" height="32"/>

                        <div class="reviews_available">Есть<br/>отзывы</div>
                    </li>
                <? endif; ?>
            </ul>
        </div>
        <div class="liquid_gap_2"></div>
        <? if (count($arResult['OFFERS']) > 1): ?>
            <div class="offer_selector">
                <? if ($arResult['DISPLAY_PROPERTIES']['SHOW_SIZES_TABLE']['VALUE']): ?>
                    <a href="/pomoshch/tablitsa-razmerov/" class="sizes_link" target="_blank">таблица размеров</a>
                <? endif; ?>
                <div class="title"><? if ($arResult['_OFFERS_VIEW'] == 1): ?>Вариант<? else: ?>Параметры<? endif; ?>:
                </div>
                <div class="clear"></div>
                <div class="gap5"></div>
                <div class="gap5"></div>
                <? if ($arResult['_OFFERS_VIEW'] == 2): ?>
                    <? foreach ($arResult['_SELECT_PARAMS'] as $k => $arValues): ?>
                        <select class="custom" data-param="<?= Cutil::translit($k, 'ru'); ?>">
                            <? foreach ($arValues as $v): ?>
                                <option
                                    value="<?= Cutil::translit($v, 'ru'); ?>"<? foreach ($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PARAMS']['VALUE'] as $m => $n): ?><? if (($n == $k) and ($arResult['_CURRENT_OFFER']['DISPLAY_PROPERTIES']['PARAMS']['DESCRIPTION'][$m] == $v)): ?> selected<? endif; ?><? endforeach; ?>><?= $v ?></option>
                            <? endforeach; ?>
                        </select>
                        <div></div>
                    <? endforeach; ?>
                <? else: ?>
                    <select class="custom" data-param="offer">
                        <? foreach ($arResult['OFFERS'] as $arOffer): ?>
                            <option
                                value="<?= $arOffer['ID'] ?>"<? if ($arOffer['ID'] == $arResult['_CURRENT_OFFER']['ID']): ?> selected<? endif; ?>><? if ($arResult['_OFFERS_VIEW'] == 1): ?><?= $arOffer['NAME'] ?><? else: ?><? foreach ($arOffer['DISPLAY_PROPERTIES']['PARAMS']['VALUE'] as $m => $n): ?><? if ($m > 0): ?>, <? endif; ?><?= $arOffer['DISPLAY_PROPERTIES']['PARAMS']['DESCRIPTION'][$m] ?><? endforeach; ?><? endif; ?></option>
                        <? endforeach; ?>
                    </select>
                <? endif; ?>
            </div>
        <? endif; ?>
    </div>
    <div class="product_gallery_part">
        <? if (count($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'])): ?>
            <? if (count($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']) > 1): ?>
                <div class="gallery_thumbs">
                    <? foreach ($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'] as $k => $arFile): ?>
                        <a itemprop="image" href="" data-slide-index="<?= $k ?>"
                           style="background-image:url(<?= phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $arFile['SRC'] . '&w=70&h=70&zc=1&f=jpg&q=85'); ?>)"></a>
                    <? endforeach; ?>
                </div>
            <? endif; ?>
            <div
                class="gallery_wrapper <? if (count($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']) == 1): ?>gallery_wrapper_no_thumbs<? endif; ?>">
                <? if (strlen($arResult['_LABEL'])): ?>
                    <div class="label"><?= $arResult['_LABEL'] ?></div>
                <? endif; ?>
                <div class="product_gallery">
                    <?
                    $counter = 0;
                    ?>
                    <? foreach ($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'] as $arFile): ?>
                        <?
                        $counter++;
                        ?>
                        <div>
                            <div
                                class="image<? if ($arResult['PROPERTIES']['NOT_CROP_IMG_' . $counter]['VALUE'] or $arResult['PROPERTIES']['NOT_CROP_ALL_IMAGES']['VALUE']): ?> not_crop_img<? endif; ?>"
                                style="background-image:url(<? if ($arFile['WIDTH'] > 1000): ?><?= phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $arFile['SRC'] . '&w=1000&h=9999f=jpg&q=85'); ?><? else: ?><?= $arFile['SRC'] ?><? endif; ?>)">
                                <div itemprop="image" class="zoomable">
                                    <img src="<?= $arFile['SRC'] ?>"/>
                                </div>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        <? elseif (count($arResult['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE'])): ?>
            <? if (count($arResult['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE']) > 1): ?>
                <div class="gallery_thumbs">
                    <? foreach ($arResult['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE'] as $k => $v): ?>
                        <a itemprop="image" href="" data-slide-index="<?= $k ?>"
                           style="background-image:url(<?= phpThumbURL('src=' . $v . '&w=70&h=70&zc=1&f=jpg&q=85'); ?>)"></a>
                    <? endforeach; ?>
                </div>
            <? endif; ?>
            <div
                class="gallery_wrapper <? if (count($arResult['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE']) == 1): ?>gallery_wrapper_no_thumbs<? endif; ?>">
                <? if (strlen($arResult['_LABEL'])): ?>
                    <div class="label"><?= $arResult['_LABEL'] ?></div>
                <? endif; ?>
                <div class="product_gallery">
                    <?
                    $counter = 0;
                    ?>
                    <? foreach ($arResult['DISPLAY_PROPERTIES']['PICTURES_LINKS']['VALUE'] as $v): ?>
                        <?
                        $counter++;
                        ?>
                        <div>
                            <div
                                class="image<? if ($arResult['PROPERTIES']['NOT_CROP_IMG_' . $counter]['VALUE'] or $arResult['PROPERTIES']['NOT_CROP_ALL_IMAGES']['VALUE']): ?> not_crop_img<? endif; ?>"
                                style="background-image:url(<?= $v ?>)">
                                <div itemprop="image" class="zoomable">
                                    <img src="<?= $v ?>"/>
                                </div>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        <? else: ?>
            <div class="gallery_wrapper gallery_wrapper_no_thumbs">
                <? if (strlen($arResult['_LABEL'])): ?>
                    <div class="label"><?= $arResult['_LABEL'] ?></div>
                <? endif; ?>
                <div class="product_gallery">
                    <div>
                        <div
                            class="image<? if ($arResult['PROPERTIES']['NOT_CROP_PREVIEW_IMG']['VALUE'] or $arResult['PROPERTIES']['NOT_CROP_ALL_IMAGES']['VALUE']): ?> not_crop_img<? endif; ?>"
                            style="background-image:url(<?= $arResult['PREVIEW_PICTURE']['SRC'] ?>)">
                            <div class="zoomable">
                                <img src="<?= $arResult['PREVIEW_PICTURE']['SRC'] ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <? endif; ?>
    </div>
    <div class="clear"></div>
    <? if (strlen($arResult['DETAIL_TEXT']) or count($arResult['DISPLAY_PROPERTIES']['CHARS']['VALUE']) or count($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES']['FILE_VALUE']) or count($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES_LINKS']['VALUE']) or intval($arResult['DISPLAY_PROPERTIES']['AUTO_CALCULATED_REVIEWS_AMOUNT']['VALUE']) or count($arResult['_ALSO_BUY'])): ?>
        <div class="tabbed_content">
            <ul class="tabs">
                <? if (count($arResult['DISPLAY_PROPERTIES']['CHARS']['VALUE'])): ?>
                    <li>
                        <a href="#tab_specs">
                            <img src="<?= $templateFolder ?>/img/icon_chars.png"/>

                            <h2>
                                Характеристики</br>
                                <span itemprop="name">
                                    <?= $arResult['NAME'] ?>
                                </span>
                            </h2>
                        </a>
                    </li>
                <? endif; ?>
                <? if (strlen($arResult['DETAIL_TEXT'])): ?>
                    <li>
                        <a href="#tab_description">
                            <img src="<?= $templateFolder ?>/img/icon_descr.png"/>

                            <h2>
                                Описание</br>
                                <span>
                                    <?= $arResult['NAME'] ?>
                                </span>
                            </h2>

                            <div></div>
                        </a>
                    </li>
                <? endif; ?>
                <? if (count($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES']['FILE_VALUE']) or count($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES_LINKS']['VALUE'])): ?>
                    <li>
                        <a href="#tab_add_photos">
                            <img src="<?= $templateFolder ?>/img/icon_photos.png"/>

                            <h2>
                                Фотографии</br>
                                <span>
                                    <?= $arResult['NAME'] ?>
                                </span>
                            </h2>
                        </a>
                    </li>
                <? endif; ?>
                <? if (intval($arResult['DISPLAY_PROPERTIES']['AUTO_CALCULATED_REVIEWS_AMOUNT']['VALUE'])): ?>
                    <li><a href="#tab_reviews">
                            <img src="<?= $templateFolder ?>/img/icon_comments.png"/>

                            <h2>
                                Отзывы о</br>
                                <span>
                                    <?= $arResult['NAME'] ?>
                                </span>
                            </h2>
                        </a>
                    </li>
                <? endif; ?>
                <? if (count($arResult['_ALSO_BUY'])): ?>
                    <li><a href="#tab_seealso"><img src="<?= $templateFolder ?>/img/icon_alsobuy.png"/>

                            <h2>
                                Покупают вместе с</br>
                                <span>
                                    <?= $arResult['NAME'] ?>
                                </span>
                            </h2>
                        </a>
                    </li>
                <? endif; ?>
            </ul>
            <div class="inner">
                <? if (count($arResult['DISPLAY_PROPERTIES']['CHARS']['VALUE'])): ?>
                    <div id="tab_specs">
                        <? if (count($arResult['_CHARS']) == 1): ?>
                            <? if ($arResult['_CHARS'][0]['GROUP_NAME']): ?>
                                <p><b><?= $arResult['_CHARS'][0]['GROUP_NAME'] ?></b></p>
                            <? endif; ?>
                            <div class="row">
                                <div class="small-12 medium-6 large-6 columns">
                                    <div class="gap20"></div>
                                    <table class="stripes">
                                        <tbody>
                                        <? foreach ($arResult['_CHARS'][0]['GROUP_ITEMS'] as $k => $arChar): ?>
                                        <tr>
                                            <td><?= $arChar['CHAR_NAME'] ?></td>
                                            <td><?= $arChar['CHAR_VALUE'] ?></td>
                                        </tr>
                                        <? if ($k == ceil(count($arResult['_CHARS'][0]['GROUP_ITEMS']) / 2)): ?>
                                        </tbody>
                                    </table>
                                    <div class="gap20"></div>
                                </div>
                                <div class="small-12 medium-6 large-6 columns">
                                    <div class="gap20"></div>
                                    <table class="stripes" cellpadding="0" cellspacing="0">
                                        <tbody>
                                        <? endif; ?>
                                        <? endforeach; ?>
                                        </tbody>
                                    </table>
                                    <div class="gap20"></div>
                                </div>
                            </div>
                        <? else: ?>
                            <div class="row">
                                <div class="small-12 medium-6 large-6 columns">
                                    <? foreach ($arResult['_CHARS'] as $k => $arCharsGroup): ?>
                                    <div class="gap20"></div>
                                    <table class="stripes">
                                        <? if ($arCharsGroup['GROUP_NAME']): ?>
                                            <thead>
                                            <tr>
                                                <td colspan="2"><?= $arCharsGroup['GROUP_NAME'] ?></td>
                                            </tr>
                                            </thead>
                                        <? endif; ?>
                                        <tbody>
                                        <? foreach ($arCharsGroup['GROUP_ITEMS'] as $arChar): ?>
                                            <tr>
                                                <td><?= $arChar['CHAR_NAME'] ?></td>
                                                <td><?= $arChar['CHAR_VALUE'] ?></td>
                                            </tr>
                                        <? endforeach; ?>
                                        </tbody>
                                    </table>
                                    <? if ($k == $arResult['_CHARS_GROUPS_DIVIDER']): ?>
                                    <div class="gap20"></div>
                                </div>
                                <div class="small-12 medium-6 large-6 columns">
                                    <? endif; ?>
                                    <? endforeach; ?>
                                    <div class="gap20"></div>
                                </div>
                            </div>
                        <? endif; ?>
                    </div>
                <? endif; ?>
                <? if (strlen($arResult['DETAIL_TEXT'])): ?>
                    <div itemprop="description" id="tab_description">
                        <?= $arResult['DETAIL_TEXT'] ?>
                    </div>
                <? endif; ?>
                <? if (count($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES']['FILE_VALUE']) or count($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES_LINKS']['VALUE'])): ?>
                    <div id="tab_add_photos">
                        <div class="gap20"></div>
                        <div class="add_photos">
                            <ul class="add_picture_bx_slider">
                                <? foreach ($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES']['FILE_VALUE'] as $arFile): ?>
                                    <li>
                                        <img
                                            src="<?= phpThumbURL('src=' . _PHPTHUMB_PATH_PREFIX . $arFile['SRC'] . '&w=1000&h=500&f=jpg&q=85'); ?>"/></a>
                                        <? if ($arFile['DESCRIPTION']): ?>
                                            <div class="gap5"></div>
                                            <div class="gap5"></div>
                                            <?= $arFile['DESCRIPTION']; ?>
                                        <? endif; ?>
                                        <div class="gap20"></div>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                            <ul class="add_picture_bx_slider">

                                <? foreach ($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES_LINKS']['VALUE'] as $k => $v): ?>
                                    <li>
                                        <img src="<?= $v; ?>"/>
                                        <? if ($arResult['DISPLAY_PROPERTIES']['ADD_PICTURES_LINKS']['DESCRIPTION'][$k]): ?>
                                            <div class="gap5"></div>
                                            <div class="gap5"></div>
                                            <?= $arResult['DISPLAY_PROPERTIES']['ADD_PICTURES_LINKS']['DESCRIPTION'][$k]; ?>
                                        <? endif; ?>
                                        <div class="gap20"></div>
                                    </li>
                                <? endforeach; ?>
                            </ul>

                        </div>
                    </div>
                <? endif; ?>
                <? if (intval($arResult['DISPLAY_PROPERTIES']['AUTO_CALCULATED_REVIEWS_AMOUNT']['VALUE'])): ?>
                    <div id="tab_reviews">
                        <div class="gap20"></div>
                        <?
                        global $arProductReviewsFilter;
                        $arProductReviewsFilter = array('PROPERTY_PRODUCT' => $arResult['ID']);
                        ?>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "product_reviews",
                            Array(
                                "AJAX_MODE" => "N",
                                "IBLOCK_TYPE" => "system",
                                "IBLOCK_ID" => "8",
                                "NEWS_COUNT" => "10",
                                "SORT_BY1" => "ID",
                                "SORT_ORDER1" => "DESC",
                                "SORT_BY2" => "",
                                "SORT_ORDER2" => "",
                                "FILTER_NAME" => "arProductReviewsFilter",
                                "FIELD_CODE" => array(),
                                "PROPERTY_CODE" => array("USER", "RATING"),
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "ACTIVE_DATE_FORMAT" => "",
                                "SET_STATUS_404" => "N",
                                "SET_TITLE" => "N",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "INCLUDE_SUBSECTIONS" => "Y",
                                "CACHE_TYPE" => $arResult["CACHE_TYPE"],
                                "CACHE_TIME" => $arResult["CACHE_TIME"],
                                "CACHE_FILTER" => "Y",
                                "CACHE_GROUPS" => "Y",
                                "PAGER_TEMPLATE" => "",
                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "PAGER_TITLE" => "",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "",
                                "PAGER_SHOW_ALL" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "AJAX_OPTION_HISTORY" => "N"
                            ),
                            false,
                            array('HIDE_ICONS' => 'Y')
                        ); ?>
                    </div>
                <? endif; ?>
                <? if (count($arResult['_ALSO_BUY'])): ?>
                    <div id="tab_seealso">
                        <div class="gap20"></div>
                        <?
                        global $arAlsoBuyProductsFilter;
                        $arAlsoBuyProductsFilter = array('ID' => $arResult['_ALSO_BUY']);
                        ?>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:catalog.section",
                            "",
                            array(
                                "IBLOCK_TYPE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["IBLOCK_TYPE"],
                                "IBLOCK_ID" => $arParams['_COMPLEX_COMPONENT_PARAMS']["IBLOCK_ID"],
                                "ELEMENT_SORT_FIELD" => $arParams['_COMPLEX_COMPONENT_PARAMS']["ELEMENT_SORT_FIELD"],
                                "ELEMENT_SORT_ORDER" => $arParams['_COMPLEX_COMPONENT_PARAMS']["ELEMENT_SORT_ORDER"],
                                "ELEMENT_SORT_FIELD2" => $arParams['_COMPLEX_COMPONENT_PARAMS']["ELEMENT_SORT_FIELD2"],
                                "ELEMENT_SORT_ORDER2" => $arParams['_COMPLEX_COMPONENT_PARAMS']["ELEMENT_SORT_ORDER2"],
                                "PROPERTY_CODE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["LIST_PROPERTY_CODE"],
                                "META_KEYWORDS" => "",
                                "META_DESCRIPTION" => "",
                                "BROWSER_TITLE" => "N",
                                "INCLUDE_SUBSECTIONS" => 'Y',
                                "SHOW_ALL_WO_SECTION" => "Y",
                                "BASKET_URL" => $arParams['_COMPLEX_COMPONENT_PARAMS']["BASKET_URL"],
                                "ACTION_VARIABLE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["ACTION_VARIABLE"],
                                "PRODUCT_ID_VARIABLE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PRODUCT_ID_VARIABLE"],
                                "SECTION_ID_VARIABLE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["SECTION_ID_VARIABLE"],
                                "PRODUCT_QUANTITY_VARIABLE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PRODUCT_QUANTITY_VARIABLE"],
                                "PRODUCT_PROPS_VARIABLE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PRODUCT_PROPS_VARIABLE"],
                                "FILTER_NAME" => 'arAlsoBuyProductsFilter',
                                "CACHE_TYPE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["CACHE_TYPE"],
                                "CACHE_TIME" => $arParams['_COMPLEX_COMPONENT_PARAMS']["CACHE_TIME"],
                                "CACHE_FILTER" => $arParams['_COMPLEX_COMPONENT_PARAMS']["CACHE_FILTER"],
                                "CACHE_GROUPS" => $arParams['_COMPLEX_COMPONENT_PARAMS']["CACHE_GROUPS"],
                                "SET_TITLE" => "N",
                                "SET_STATUS_404" => "N",
                                "DISPLAY_COMPARE" => "N",
                                "PAGE_ELEMENT_COUNT" => 18,
                                "LINE_ELEMENT_COUNT" => $arParams['_COMPLEX_COMPONENT_PARAMS']["LINE_ELEMENT_COUNT"],
                                "PRICE_CODE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PRICE_CODE"],
                                "USE_PRICE_COUNT" => $arParams['_COMPLEX_COMPONENT_PARAMS']["USE_PRICE_COUNT"],
                                "SHOW_PRICE_COUNT" => $arParams['_COMPLEX_COMPONENT_PARAMS']["SHOW_PRICE_COUNT"],

                                "PRICE_VAT_INCLUDE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PRICE_VAT_INCLUDE"],
                                "USE_PRODUCT_QUANTITY" => $arParams['_COMPLEX_COMPONENT_PARAMS']['USE_PRODUCT_QUANTITY'],
                                "ADD_PROPERTIES_TO_BASKET" => (isset($arParams['_COMPLEX_COMPONENT_PARAMS']["ADD_PROPERTIES_TO_BASKET"]) ? $arParams['_COMPLEX_COMPONENT_PARAMS']["ADD_PROPERTIES_TO_BASKET"] : ''),
                                "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams['_COMPLEX_COMPONENT_PARAMS']["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams['_COMPLEX_COMPONENT_PARAMS']["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                                "PRODUCT_PROPERTIES" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PRODUCT_PROPERTIES"],

                                "DISPLAY_TOP_PAGER" => "N",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "PAGER_TITLE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PAGER_TITLE"],
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_TEMPLATE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PAGER_TEMPLATE"],
                                "PAGER_DESC_NUMBERING" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PAGER_DESC_NUMBERING"],
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PAGER_DESC_NUMBERING_CACHE_TIME"],
                                "PAGER_SHOW_ALL" => $arParams['_COMPLEX_COMPONENT_PARAMS']["PAGER_SHOW_ALL"],

                                "OFFERS_CART_PROPERTIES" => $arParams['_COMPLEX_COMPONENT_PARAMS']["OFFERS_CART_PROPERTIES"],
                                "OFFERS_FIELD_CODE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["LIST_OFFERS_FIELD_CODE"],
                                "OFFERS_PROPERTY_CODE" => $arParams['_COMPLEX_COMPONENT_PARAMS']["LIST_OFFERS_PROPERTY_CODE"],
                                "OFFERS_SORT_FIELD" => $arParams['_COMPLEX_COMPONENT_PARAMS']["OFFERS_SORT_FIELD"],
                                "OFFERS_SORT_ORDER" => $arParams['_COMPLEX_COMPONENT_PARAMS']["OFFERS_SORT_ORDER"],
                                "OFFERS_SORT_FIELD2" => $arParams['_COMPLEX_COMPONENT_PARAMS']["OFFERS_SORT_FIELD2"],
                                "OFFERS_SORT_ORDER2" => $arParams['_COMPLEX_COMPONENT_PARAMS']["OFFERS_SORT_ORDER2"],
                                "OFFERS_LIMIT" => $arParams['_COMPLEX_COMPONENT_PARAMS']["LIST_OFFERS_LIMIT"],

                                'CONVERT_CURRENCY' => $arParams['_COMPLEX_COMPONENT_PARAMS']['CONVERT_CURRENCY'],
                                'CURRENCY_ID' => $arParams['_COMPLEX_COMPONENT_PARAMS']['CURRENCY_ID'],
                                'HIDE_NOT_AVAILABLE' => $arParams['_COMPLEX_COMPONENT_PARAMS']["HIDE_NOT_AVAILABLE"],

                                "ADD_SECTIONS_CHAIN" => "N",
                            ),
                            false
                        );
                        ?>
                    </div>
                <? endif; ?>
            </div>
        </div>
    <? endif; ?>
</div>