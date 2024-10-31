<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $APPLICATION
 */
\Bitrix\Main\Loader::includeModule('ibs.laptop_store');

$GLOBALS['arFilter'] = [
    'MODEL.BRAND.NAME' => $arResult['VARIABLES']['BRAND'],
    'MODEL.NAME' => $arResult['VARIABLES']['MODEL'],
];
$fopFields = [];
$fields = LaptopStore\Entity\OptionTable::getList();
while ($field = $fields->fetch()) {
    $fopFields[] = [
        'id' => $field['NAME'],
        'name' => $field['NAME'],
        'sort' => 'OPTIONS.VALUE',
        'default' => true
    ];
}


$APPLICATION->IncludeComponent(
	"ibs:laptop.list", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"FILTER_NAME" => "arFilter",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"DOP_FIELDS" => $fopFields,
        "DETAIL_PAGE_URL" => $arParams['SEF_FOLDER'].'detail/#code#/',
        "GRID_ID" => "list_grid_id",
		"ENTITY" => "LaptopStore\\Entity\\LapTopTable",
        "OPTIONS" => ['MODEL_' => 'MODEL.NAME', 'BRAND_' => 'MODEL.BRAND.NAME', 'OPTIONS_' => 'OPTIONS.VALUE', 'OPTIONS_NAME' => 'OPTIONS.OPTION.NAME']
	),
	$component
);