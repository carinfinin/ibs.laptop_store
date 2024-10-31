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
        //******************************************************//
        // Обработка GET параметров                             //
        //******************************************************//

        // дополнительные GET параметры которые будем отлавливать в запросе, в массив $arVariables будет добавлена переменная sort, значение которой будет получено из $_REQUEST['sort'], применяется когда не нужно указывать точный псевдоним для ключа
        $arComponentVariables = [
            'sort'
        ];

        // дополнительные GET параметры которые будем отлавливать в запросе, полезно например для постраничной навигации. В массив $arVariableAliases будет добавлена переменная ELEMENT_COUNT, значение которой будет получено из $_REQUEST['count'], отлавливаться параметр будет только в разделе section, в итоге данные попадут в $arVariables, применяется когда нужно указать точный псевдоним для ключа 
        $arDefaultVariableAliases404 = array(
            'section' => array(
                'ELEMENT_COUNT' => 'count',
            )
        );
        /* метод предназначен для объединения дефолтных GET
        параметров которые приходят в $arParams["VARIABLE_ALIASES"],
        в режиме ЧПУ $arParams["VARIABLE_ALIASES"] будет пустой и дополнительных GET параметров из массива $arDefaultVariableAliases404.
         Параметры из настроек $arrParams заменяют дополнительные из $arDefaultVariableAliases404
        */
        $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
            $arDefaultVariableAliases404,
            $this->arParams["VARIABLE_ALIASES"]
        );

        //*****************************************************//
        // Обработка данных по маске из URL запроса           //
        //*****************************************************//

        $arDefaultUrlTemplates404 = [
            "section" => "#BRAND#/",
            "list" => "#BRAND#/#MODEL#/",
            "element" => "detail/#NOTEBOOK#/",
        ];

        // метод предназначен для объединения дефолтных параметров масок URL которые приходят в arParams["SEF_URL_TEMPLATES"] и из массива $arDefaultUrlTemplates404. Параметры из настроек $arrParams заменяют дефолтные из $arDefaultUrlTemplates404
        $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates(
            // массив переменных с масками по умолчанию
            $arDefaultUrlTemplates404,
            // массив переменных с масками из входных параметров $arParams["SEF_URL_TEMPLATES"]
            $this->arParams["SEF_URL_TEMPLATES"]
        );

        //*****************************************************//
        // Получение шаблона для подключения                   //
        //*****************************************************//

        // объект для поиска шаблонов
        $engine = new CComponentEngine($this);

        // главная переменная комплексного компонента, именно она будут записана в массив $arResult, как результат работы комплексного компонента. Она будет доступна в файлах section.php, element.php, index.php, которые будут подключены, после того как отработает class.php
        $arVariables = [];

        // определение шаблона, какой файл подключать section.php, element.php, index.php и заполнение $arVariables получеными URL в соответствие с масками
        $requestURL = Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPage();
        // проверка на детальную из за совпадения с url модели
        if (str_contains($requestURL, 'detail')) {
            $componentPage = 'element';
        }else {
            $componentPage = $engine->guessComponentPath(
            // путь до корня секции
                $this->arParams["SEF_FOLDER"],
                // массив масок
                $arUrlTemplates,
                // путь до секции SECTION_CODE и элемента ELEMENT_CODE
                $arVariables,
                $requestURL
            );

            if ($componentPage == false) {
                $componentPage = 'index';
            }
        }


        //*****************************************************//
        // Формируем $arResult                                 //
        //*****************************************************//

        // метод предназначен для объединения GET и URL параметров, результат записываем в $arVariables
        CComponentEngine::initComponentVariables(
            // нужен для режима ЧПУ, содержит файл который будет подключен section.php, element.php, index.php
            $componentPage,
            // массив дополнительных GET параметров без псевдонимов
            $arComponentVariables,
            // массив основных GET параметров с псевдонимами
            $arVariableAliases,
            // обьединяем все найденные URL и GET параметры и записываем в переменну
            $arVariables
        );

        // формируем arResult
        $this->arResult = [
            // данные полученые из GET и URL параметров 
            "VARIABLES" => $arVariables,
            // массив с параметрами псевдонимов для возможности востановления дальше в обычном компоненте
            "ALIASES" => $arVariableAliases
        ];

        return $componentPage;
    }

    // метод обработки режима без ЧПУ
    protected function noSefMode()
    {
        ShowError(Loc::getMessage('errorNotSafeMode'));
        exit();
    }
}
