<?php

/**
 * Created by PhpStorm.
 * User: Asus-
 * Date: 29.05.2015
 * Time: 20:32
 */
class EventHandler
{

    function OnAfterIBlockSectionUpdateHandler(&$arSectionFields)
    {
        global $USER;


        if (($arSectionFields['IBLOCK_ID'] == 1) && $arSectionFields['RESULT']) {

            if ($arSectionFields['UF_TAOBAO_ID']) {

                $dbRes = CIBlockElement::GetList(
                    array(),
                    array(
                        'IBLOCK_CODE' => "import_profiles",
                        'PROPERTY_DEST_SECTION' => $arSectionFields['ID']
                    ),
                    false,
                    false,
                    array('IBLOCK_ID,"ID', 'PROPERTY_DEST_SECTION')
                );
                if (!($res = $dbRes->GetNext())) {
                   /* if ($USER->IsAdmin()) {*/
                        $el = new CIBlockElement();
                        $el->Add(
                            array(
                                'IBLOCK_ID' => 15,
                                "NAME" => $arSectionFields['NAME'],
                                "PROPERTY_VALUES" => array(
                                    "DEST_SECTION" => $arSectionFields['ID'],
                                    "OT_SETTINGS_CategoryId" => $arSectionFields['UF_TAOBAO_ID'],
                                    "DATA_SOURCE" => 17,
                                    "OT_SETTINGS_Provider" => 18,
                                    "OT_SETTINGS_SearchMethod" => 19,
                                    "OT_SETTINGS_LanguageOfQuery" => 23,
                                    "OT_SETTINGS_OrderBy" => 25,
                                    "PROFIT" => 30,
                                    "MAX_QUANTITY" => 200,
                                    "ONE_TIME_QUANTITY" => 50,
                                    "TIME_DELETE_AFTER" => 100,
                                    "PRICE_REDUCE" => 1
                                ),

                            ),
                            false,
                            false,
                            false
                        );
                        //file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/test/after.txt', print_r($el->LAST_ERROR . ' ' . $res, true));

                   /* }*/
                }

            }

        }
    }


}