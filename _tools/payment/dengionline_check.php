<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

function sendResponse($status, $message = ''){
	$response  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$response .= '<result>'."\n";
	$response .= '<code>'.$status.'</code>'."\n";
	$response .= '<comment>'.$message.'</comment>'."\n";
	$response .= '</result>';
	die($response);
}

$secretKey = '!!!1q2w3e4r5t6y7u8i9o0p1az2sx3dc4fv5gb6hn!!!';
$projectHash = md5($_REQUEST['amount'].$_REQUEST['userid'].$_REQUEST['paymentid'].$secretKey);
if($projectHash != $_REQUEST['key']){
	sendResponse('NO', 'Контрольная подпись запроса ошибочна');
} else {
	$arOrder = CSaleOrder::GetByID(intval(trim($_REQUEST['userid'])));
	if(!$arOrder['ID']) {
		sendResponse('NO', 'Заказ не найден');
	} else {
		if(floatval($_REQUEST['amount']) == 0 && intval($_REQUEST['paymentid']) == 0){
			sendResponse('YES', 'Заказ существует');
		} elseif(filter_var($_REQUEST['amount'], FILTER_VALIDATE_FLOAT) && filter_var($_REQUEST['paymentid'], FILTER_VALIDATE_INT) && floatval($_REQUEST['amount']) > 0 && intval($_REQUEST['paymentid']) > 0) {
			if($arOrder['PAYED'] == 'Y') {
				sendResponse('YES', 'Платеж был успешно выполнен ранее');	
			} else {
				if((intval(trim($_REQUEST['amount'])) == intval($arOrder['PRICE'])) or (intval(trim($_REQUEST['amount'])) == (intval($arOrder['PRICE'])+1)) or (intval(trim($_REQUEST['amount'])) == (intval($arOrder['PRICE'])-1))) {
					CSaleOrder::PayOrder($arOrder['ID'], "Y", true, true, 0, array("PAY_VOUCHER_NUM" => intval($_REQUEST['paymentid'])));
					sendResponse('YES', 'Платеж выполнен');	
				} else {
					sendResponse('NO', 'Сумма платежа не совпадает со стоимостью заказа, платеж не выполнен');
				}
			}
		}
	}
}
sendResponse('NO', 'Ошибка');
?>