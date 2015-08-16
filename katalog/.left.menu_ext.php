<?php
/**
 * Created by PhpStorm.
 * User: Asus-
 * Date: 16.08.2015
 * Time: 1:37
 */


$cache = new CPHPCache;
$cacheTTL = (7 * 24 * 60 * 60); // one week
$cacheID = 'CatalogBrandsAdditionalDataCache';
$cacheDir = '/custom_cache/';

if ($cache->InitCache($cacheTTL, $cacheID, $cacheDir)) {
    $aMenuLinks = $cache->GetVars();
} else {
    CModule::IncludeModule('iblock');
    $rsBrands = CIBlockElement::GetList(
        array("SORT" => "ASC"),
        array(
            'IBLOCK_ID'=>11
        ),
        false,
        false,
        array(
            'ID',
            'IBLOCK_ID',
            'NAME',
            'CODE'
        ));
    while ($arBrand = $rsBrands->GetNext()) {
        $aMenuLinks[] = [
            $arBrand['NAME'],
            "/katalog/brend/" . $arBrand['CODE'] . '/',
            [],
            [],
            ''
        ];
    }
    if ($cache->StartDataCache($cacheTTL, $cacheID, $cacheDir)) {
        $cache->EndDataCache($aMenuLinks);
    }

}
/*test_dump($aMenuLinks);*/


/*$aMenuLinks = array_merge($aMenuLinks, $aMenuLinks_ext);*/