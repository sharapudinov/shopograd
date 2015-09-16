<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
CModule::IncludeModule('iblock');
$file = array();
for ($index = 1; $index <= 18; $index++) {
    $file = array_merge($file, file($index . '.csv'));
}

$file =
    array_filter(
        array_map(
            function ($row) {
                $row=array_values(
                        array_filter(
                            array_map(
                                function ($item) {
                                    return trim($item);
                                },
                                explode(';', $row)
                            ),
                            function ($item) {
                                return $item != '';
                            }
                        )
                    );
                $row[0] = explode(']', explode("[", $row[0])[1])[0];
                return $row[0] == '' || $row[1] == '' ? null : $row;
            },
            $file
        ),
        function ($item) {
            return count($item) > 1;
        }
    );


$obSection = new CIBlockSection();

echo '<pre>';
/*foreach ($file as $section) {
    $dbSec = CIBlockSection::GetList(
        array(),
        array(
            'IBLOCK_ID' => 1,
            'UF_TAOBAO_ID' => $section[0]),
        false,
        array('ID')
    );
    echo 'UF_TAOBAO_ID= '.$section['0'].PHP_EOL;
    while ($sec = $dbSec->GetNext()) {
        echo $sec['ID'].' '.$section[1].PHP_EOL;
        $obSection->Update($sec['ID'], array('UF_WEIGHT' => $section[1]));
    }
}*/
echo '</pre>';

?>

    Text here....

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>