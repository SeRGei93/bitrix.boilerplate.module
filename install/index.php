<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

IncludeModuleLangFile(__FILE__);

if (class_exists('boilerplate_module')) {
    return;
}

class boilerplate_module extends CModule
{
    public $MODULE_ID = 'boilerplate.module';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('BOILERPLATE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('BOILERPLATE_MODULE_DESC');
        $this->PARTNER_NAME = '';
    }

    /**
     * Get application folder.
     * @return string /document/local (when exists) or /document/bitrix
     */
    public static function getRoot()
    {
        $local = $_SERVER['DOCUMENT_ROOT'] . '/local';
        if (1 === preg_match('#local[\\\/]modules#', __DIR__) && is_dir($local)) {
            return $local;
        }

        return $_SERVER['DOCUMENT_ROOT'] . BX_ROOT;
    }

    private static function deleteComponent($componentName)
    {
        DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/components/opensource/' . $componentName);
        DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT'] . '/local/components/opensource/' . $componentName);
    }

    function DoInstall()
    {
        global $DB;

        /**
         * Install Database
         */
        // $DB->RunSQLBatch(__DIR__ . '/install/db/install.sql');

        /**
         * Install Events
         */

        /**
         * Install Files
         */
        CopyDirFiles(__DIR__ . '/components', static::getRoot() . '/components', true, true);

        ModuleManager::RegisterModule($this->MODULE_ID);
    }

    function DoUninstall()
    {
        /**
         * Uninstall Database
         */
        // $DB->RunSQLBatch(__DIR__ . '/install/db/uninstall.sql');

        /**
         * Uninstall Events
         */

        /**
         * Uninstall Files
         */
        static::deleteComponent('boilerplate.component');

        UnRegisterModule($this->MODULE_ID);
    }
}