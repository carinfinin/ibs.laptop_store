<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $APPLICATION
 */

$APPLICATION->IncludeComponent(
    "ibs:laptop.detail",
    ".default",
    array(
        "CACHE_TIME" => "3600",
        "ELEMENT_CODE" => $arResult['VARIABLES']['NOTEBOOK'],
    ),
    $component
);