<?php
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager,
    Bitrix\Main\Entity\Base,
    Bitrix\Main\EventManager,
    Bitrix\Main\Config\Option;

class Ibs_laptop_store extends CModule {
    public  $MODULE_ID;
    public  $MODULE_VERSION;
    public  $MODULE_VERSION_DATE;
    public  $MODULE_NAME;
    public  $MODULE_DESCRIPTION;
    public  $PARTNER_NAME;
    public  $PARTNER_URI;

    public $DbTables = [
        'LaptopStore\Entity\BrandTable',
        'LaptopStore\Entity\LapTopTable',
        'LaptopStore\Entity\LapTopOptionTable',
        'LaptopStore\Entity\ModelTable',
        'LaptopStore\Entity\OptionTable',
    ];

    public function __construct()
    {
        $arModuleVersion = array();
        include_once(__DIR__ . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_ID = "ibs.laptop_store";
        $this->MODULE_NAME = "laptop_store";
        $this->MODULE_DESCRIPTION = "Тестовое задание";
        $this->PARTNER_NAME = "ibs";
        $this->PARTNER_URI = "https://hibs.ru";
    }

    public function DoInstall()
    {
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        global $APPLICATION;

        if ($request["step"] < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('INSTALL_TITLE_STEP_1'),
                __DIR__ . '/instal-step1.php'
            );
        }
        if ($request["step"] == 2) {

            ModuleManager::RegisterModule($this->MODULE_ID);
            $this->InstallDB();
            $this->addData();
            $this->InstallEvents();
            $this->InstallFiles();
            $this->installAgents();
        }

        return true;
    }
    public function DoUninstall()
    {

        global $APPLICATION;
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if ($request["step"] < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('DEINSTALL_TITLE_1'),
                __DIR__ . '/step1.php'
            );
        }

        if ($request["step"] == 2) {
            $this->UnInstallDB();
            // проверяим ответ формы введеный пользователем на первом шаге
            if ($request["save_data"] == "Y") {
                $this->UnInstallDB();
            }

            $this->UnInstallEvents();
            $this->UnInstallFiles();
            ModuleManager::UnRegisterModule($this->MODULE_ID);

        }

        return true;
    }

    public function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);


        foreach ($this->DbTables as $table) {

            if (!Application::getConnection()->isTableExists(Base::getInstance($table)->getDBTableName())) {
                Base::getInstance($table)->createDbTable();
            }
        }

    }

    public function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);
        foreach ($this->DbTables as $table) {
            Application::getConnection()->queryExecute('DROP TABLE IF EXISTS ' . Base::getInstance($table)->getDBTableName());
        }

        Option::delete($this->MODULE_ID);
    }

    public function addData()
    {

        $data = json_decode(file_get_contents(__DIR__.'/data/json/data.json'), true);

        $application = Application::getInstance();
        $connection = $application->getConnection();

        $options = [];
        $brand = new \LaptopStore\Entity\BrandTable();
        $model = new \LaptopStore\Entity\ModelTable();
        $laptop = new \LaptopStore\Entity\LapTopTable();
        $option = new \LaptopStore\Entity\OptionTable();
        $lapTopOption = new \LaptopStore\Entity\LapTopOptionTable();

        foreach ($data['brands'] as $arBrand) {

            try {
                // 1 transaction
                $connection->startTransaction();

                $res = $brand->add([
                    'NAME' => $arBrand['name'],
                    'CODE' => $arBrand['code'],
                    'ACTIVE' => 'Y',
                ]);

                if(!$res->isSuccess())
                    throw new \Bitrix\Main\SystemException('error save');

                //models
                foreach ($arBrand['models'] as $arModel) {


                    $resModel = $model->add([
                        'NAME' => $arModel['name'],
                        'CODE' => $arModel['code'],
                        'ACTIVE' => 'Y',
                        'BRAND_ID' => $res->getId(),
                    ]);

                    if(!$resModel->isSuccess()) {
                        $connection->rollbackTransaction();
                    }

                    //laptops
                    foreach ($arModel['laptops'] as $arLaptop) {

                        $resLaptop = $laptop->add([
                            'NAME' => $arLaptop['name'],
                            'CODE' => $arLaptop['code'],
                            'MODEL_ID' => $resModel->getId(),
                            'ACTIVE' => 'Y',
                        ]);
                        if(!$resLaptop->isSuccess()) {
                            $connection->rollbackTransaction();
                        }

                        //options
                        foreach ($arLaptop['options'] as $arOption) {
                            if(!in_array($arOption['name'], $options)) {
                                $resOption = $option->add([
                                    'NAME' => $arOption['name'],
                                    'ACTIVE' => 'Y'
                                ]);
                                if($resOption->isSuccess())
                                    $options[$resOption->getId()] = $arOption['name'];
                                // если ошибка пропускаем идём к следующему
                            }else {
                                $resLapTopOption = $lapTopOption->add([
                                    'VALUE' => $arOption['value'],
                                    'LAPTOP_ID' => $resLaptop->getId(),
                                    'OPTION_ID' => array_search($arOption['name'], $options),
                                ]);
                            }
                        }

                    }

                }

                // 1 transaction
                $connection->commitTransaction();
            } catch (\Throwable $e) {
                // 1 transaction
                $connection->rollbackTransaction();
                throw $e;
            }
        }
    }

    public function InstallEvents()
    {

    }
    public function installAgents()
    {

    }
}