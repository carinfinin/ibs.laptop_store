<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var  $arResult
 * @var  $APPLICATION

 */

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID'    => $arResult['gridId'],
        'COLUMNS'    => $arResult['columns'],
        'ROWS'       => $arResult['LIST'],
        'NAV_OBJECT' => $arResult['nav'],
        'PAGE_SIZES' => $arResult['pageSizes'],

        "AJAX_MODE" => "Y",
        'AJAX_ID'   => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'AJAX_OPTION_JUMP'          => 'N',
        'AJAX_OPTION_HISTORY'       => 'N' ,

        'SHOW_ROW_CHECKBOXES' => false,
        'SHOW_CHECK_ALL_CHECKBOXES' => false,
        'SHOW_ROW_ACTIONS_MENU'     => true,
        'SHOW_GRID_SETTINGS_MENU'   => true,
        'SHOW_NAVIGATION_PANEL'     => true,
        'SHOW_PAGINATION'           => true,

        'SHOW_SELECTED_COUNTER'     => false,
        'SHOW_TOTAL_COUNTER'        => true,
        'SHOW_PAGESIZE'             => true,
        'ALLOW_COLUMNS_SORT'        => true,
        'ALLOW_COLUMNS_RESIZE'      => true,
        'ALLOW_HORIZONTAL_SCROLL'   => true,
        'ALLOW_SORT'                => true,
        'ALLOW_PIN_HEADER'          => true,
        'SHOW_ACTION_PANEL'         => true,
    ]
);

