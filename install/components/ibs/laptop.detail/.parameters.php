<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentParameters = array(
    'PARAMETERS' => array(

        "ELEMENT_CODE" => [
            "PARENT" => "BASE",
            "NAME" => Loc::GetMessage("ELEMENT_CODE"),
            "TYPE" => "STRING",
            "DEFAULT" => "list_grid_id",
        ],
        'CACHE_TIME' => array(
            'DEFAULT' => 3600
        ),
    ),
);


//LaptopStore\Entity\LapTopOptionTable
//LaptopStore\Entity\OptionTable