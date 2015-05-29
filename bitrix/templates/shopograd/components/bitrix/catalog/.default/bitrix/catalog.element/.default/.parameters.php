<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"_OFFER_ID" => Array(
		"NAME" => 'Current offer ID',
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"_FREE_DELIVERY_FROM" => Array(
		"NAME" => 'Free delivery for products with price starting from... (number)',
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"_COMPLEX_COMPONENT_PARAMS" => Array(
		"NAME" => 'Complex component params',
		"TYPE" => "LIST",
		"DEFAULT" => "",
	)
);
?>
