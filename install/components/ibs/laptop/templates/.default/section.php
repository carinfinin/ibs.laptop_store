<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $APPLICATION
 */

$GLOBALS['arFilter'] = [
    'BRAND.NAME' => $arResult['VARIABLES']['BRAND'],
];


$APPLICATION->IncludeComponent(
    "ibs:laptop.list",
    ".default",
    array(
        "CACHE_TIME" => "3600",
        "FILTER_NAME" => "arFilter",
        "CACHE_TYPE" => "N",
        "COMPONENT_TEMPLATE" => ".default",
        "DETAIL_PAGE_URL" => $arResult['DETAIL_PAGE_URL'],
        "GRID_ID" => "list_grid_id",
        "ENTITY" => "LaptopStore\Entity\ModelTable",
        "OPTIONS" => ['BRAND_' => 'BRAND.NAME']
    ),
    $component
);