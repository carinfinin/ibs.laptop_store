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
$dopFields = [];
$fields = LaptopStore\Entity\OptionTable::getList();
while ($field = $fields->fetch()) {
    $dopFields[] = [
        'id' => $field['NAME'],
        'name' => $field['NAME'],
        'sort' => 'OPTIONS.VALUE',
        'default' => true
    ];
}

$dopFields[] = [
    'id' => 'BRAND',
    'name' => 'BRAND',
    'sort' => '',
    'default' => true
];


$APPLICATION->IncludeComponent(
	"ibs:laptop.list", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"FILTER_NAME" => "arFilter",
		"CACHE_TYPE" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"DOP_FIELDS" => $dopFields,
        "DETAIL_PAGE_URL" => $arResult['DETAIL_PAGE_URL'],
        "GRID_ID" => "list_grid_id",
		"ENTITY" => "LaptopStore\\Entity\\LapTopTable",
        "OPTIONS" => ['MODEL_' => 'MODEL.NAME', 'BRAND_' => 'MODEL.BRAND.NAME', 'OPTIONS_' => 'OPTIONS.VALUE', 'OPTIONS_NAME' => 'OPTIONS.OPTION.NAME']
	),
	$component
);