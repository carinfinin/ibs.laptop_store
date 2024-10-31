<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentParameters = [
    // основной массив с параметрами
    "PARAMETERS" => [
        "SEF_MODE" => [
            "section" => [
                "NAME" => Loc::GetMessage("NAME_SECTION"),
                "DEFAULT" => "#BRAND#/",
            ],
            "list" => [
                "NAME" => Loc::GetMessage("NAME_LIST"),
                "DEFAULT" => "#BRAND#/#MODEL#/",
            ],
            "element" => [
                "NAME" => Loc::GetMessage("NAME_ELEMENT"),
                "DEFAULT" => "detail/#NOTEBOOK#/",
            ]
        ],
    ]
];
