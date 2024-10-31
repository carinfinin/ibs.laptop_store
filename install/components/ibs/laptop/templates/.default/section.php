<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $APPLICATION
 */
echo "<pre>";
print_r('model');
echo "</pre>";
$GLOBALS['arFilter'] = [
    'BRAND.NAME' => $arResult['VARIABLES']['BRAND'],
];


$APPLICATION->IncludeComponent(
    "ibs:laptop.list",
    ".default",
    array(
        "CACHE_TIME" => "3600",
        "FILTER_NAME" => "arFilter",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => ".default",
        "DETAIL_PAGE_URL" => $arParams['SEF_FOLDER'].$arResult['VARIABLES']['BRAND'].'/#code#/',
        "GRID_ID" => "list_grid_id",
        "ENTITY" => "LaptopStore\Entity\ModelTable",
        "OPTIONS" => ['BRAND_' => 'BRAND.NAME']
    ),
    $component
);