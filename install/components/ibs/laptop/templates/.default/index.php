<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $APPLICATION
 */

$APPLICATION->IncludeComponent(
	"ibs:laptop.list", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"FILTER_NAME" => "arFilter",
		"CACHE_TYPE" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"DETAIL_PAGE_URL" => $arResult["DETAIL_PAGE_URL"],
		"GRID_ID" => "list_grid_id",
		"ENTITY" => "LaptopStore\\Entity\\BrandTable",
		"OPTIONS" => array(
			0 => "",
			1 => "",
		),
		"DOP_FIELDS" => ""
	),
	false
);