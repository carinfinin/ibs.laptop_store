<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
echo "<pre>";
print_r('brand');
echo "</pre>";

$APPLICATION->IncludeComponent(
    "ibs:laptop.list",
    ".default",
    array(
        "CACHE_TIME" => "3600",
        "FILTER_NAME" => "arFilter",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => ".default",
        "DETAIL_PAGE_URL" => $arParams['SEF_FOLDER'].'#code#/',
        "GRID_ID" => "list_grid_id",
        "ENTITY" => "LaptopStore\\Entity\\BrandTable",
        "OPTIONS" => []
    ),
    false
);