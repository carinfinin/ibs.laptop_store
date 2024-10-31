<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentParameters = array(
    'PARAMETERS' => array(
        "OPTIONS" => [
            "PARENT" => "BASE",
            "NAME" => Loc::GetMessage("OPTIONS"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "ADDITIONAL_VALUES" => "Y",
        ],

        "ENTITY" => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::GetMessage("ENTITY"),
            'TYPE' => 'LIST',
            'VALUES' => [
                'LaptopStore\Entity\BrandTable' => 'Бренды',
                'LaptopStore\Entity\ModelTable' => 'Модели',
                'LaptopStore\Entity\LapTopTable' => 'Ноутбуки',
            ],
            'REFRESH' => 'N',
            'DEFAULT' => 'news',
            'MULTIPLE' => 'N',
        ),
        "FILTER_NAME" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => Loc::GetMessage("T_IBLOCK_FILTER"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "GRID_ID" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => Loc::GetMessage("GRID_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => "list_grid_id",
        ],
        "DETAIL_PAGE_URL" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => Loc::GetMessage("DETAIL_PAGE_URL"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "DOP_FIELDS" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => Loc::GetMessage("DOP_FIELDS"),
            "TYPE" => "LIST",
        ],
        'CACHE_TIME' => array(
            'DEFAULT' => 3600
        ),
    ),
);


//LaptopStore\Entity\LapTopOptionTable
//LaptopStore\Entity\OptionTable