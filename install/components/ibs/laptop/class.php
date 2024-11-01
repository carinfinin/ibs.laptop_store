<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
class ComplexComponent extends CBitrixComponent
{
    public function executeComponent()
    {

        if ($this->arParams["SEF_MODE"] === "Y") {
            $componentPage = $this->sefMode();
        }

        if ($this->arParams["SEF_MODE"] != "Y") {
            $componentPage = $this->noSefMode();
        }

        if (!$componentPage) {
            Tools::process404(
                $this->arParams["MESSAGE_404"],
                ($this->arParams["SET_STATUS_404"] === "Y"),
                ($this->arParams["SET_STATUS_404"] === "Y"),
                ($this->arParams["SHOW_404"] === "Y"),
                $this->arParams["FILE_404"]
            );
        }

        $this->IncludeComponentTemplate($componentPage);
    }

    // ЧПУ
    protected function sefMode()
    {
        $arComponentVariables = [
            'sort'
        ];

        $arDefaultVariableAliases404 = array(
            'section' => array(
                'ELEMENT_COUNT' => 'count',
            )
        );

        $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
            $arDefaultVariableAliases404,
            $this->arParams["VARIABLE_ALIASES"]
        );


        $arDefaultUrlTemplates404 = [
            "section" => "#BRAND#/",
            "list" => "#BRAND#/#MODEL#/",
            "element" => "detail/#NOTEBOOK#/",
        ];

        $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates(
            $arDefaultUrlTemplates404,
            $this->arParams["SEF_URL_TEMPLATES"]
        );

        $engine = new CComponentEngine($this);

        $arVariables = [];

        $requestURL = Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPage();
        if (str_contains($requestURL, 'detail')) {
            $componentPage = $engine->guessComponentPath(
                $this->arParams["SEF_FOLDER"],
                ['element' => $arUrlTemplates['element']],
                $arVariables,
                $requestURL
            );
        }else {
            $componentPage = $engine->guessComponentPath(
                $this->arParams["SEF_FOLDER"],
                $arUrlTemplates,
                $arVariables,
                $requestURL
            );

            if ($componentPage == false) {
                $componentPage = 'index';
            }
        }



        $detailPageUrl = $this->arParams["SEF_FOLDER"].'#code#/';
        if($componentPage == 'section') {
            $detailPageUrl = $this->arParams["SEF_FOLDER"].str_replace(['#BRAND#', '#MODEL#'], [$arVariables['BRAND'], '#code#'], $this->arParams["SEF_URL_TEMPLATES"]["list"]);
        }else if($componentPage == 'list') {
            $detailPageUrl = $this->arParams["SEF_FOLDER"].str_replace(['#BRAND#', '#MODEL#', '#NOTEBOOK#'], [$arVariables['BRAND'],$arVariables['MODEL'], '#code#'], $this->arParams["SEF_URL_TEMPLATES"]["element"]);
        }



        CComponentEngine::initComponentVariables(
            $componentPage,
            $arComponentVariables,
            $arVariableAliases,
            $arVariables
        );

        $this->arResult = [
            "VARIABLES" => $arVariables,
            "ALIASES" => $arVariableAliases,
            "DETAIL_PAGE_URL" => $detailPageUrl
        ];

        return $componentPage;
    }

    protected function noSefMode()
    {
        ShowError(Loc::getMessage('errorNotSafeMode'));
        exit();
    }
}
