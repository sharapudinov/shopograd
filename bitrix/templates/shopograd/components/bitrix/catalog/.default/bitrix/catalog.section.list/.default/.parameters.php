<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"_GLOBAL_SEARCH_TYPE" => Array(
		"NAME" => 'Global search type (1 = by phrase, 2 = by brand)',
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"_GLOBAL_SEARCH_CONDITION" => Array(
		"NAME" => 'Global search condition (search phrase or brand)',
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"_GLOBAL_SEARCH_FILTER" => Array(
		"NAME" => 'Filter to count elements in sections and calculate prices range',
		"TYPE" => "LIST",
		"DEFAULT" => "",
	),
	"_FILTER_AND_SORTING" => Array(
		"NAME" => 'Params of filtration and sorting of elements in section (see filter_and_sorting.php in complex component template)',
		"TYPE" => "LIST",
		"DEFAULT" => "",
	),
	"_CURRENCY" => Array(
		"NAME" => 'View currency (for prices)',
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"_FILTER_AND_SORTING_S" => Array(
		"NAME" => 'Serialized array to save caching!  Params of filtration and sorting of elements in section (see filter_and_sorting.php in complex component template)',
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"_GLOBAL_SEARCH_FILTER_S" => Array(
		"NAME" => 'Serialized array to save caching! Filter to count elements in sections',
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"_VIEW_IMAGES" => Array(
		"NAME" => 'View sections with images',
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
);
?>