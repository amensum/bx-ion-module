<?php
require __DIR__ . '/version.php';

use Ion\Main;
use Bitrix\Main\EventManager;

/**
 * Class IonModule
 * @module Ion
 */
class Ion extends CModule
{
	public $MODULE_ID = "ion";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $MODULE_DIR;

	/**
	 * Ion constructor.
	 */
	public function __construct()
	{
		$this->MODULE_VERSION = ION_VERSION;
		$this->MODULE_VERSION_DATE = "2020-03-01 18:00";
		$this->MODULE_NAME = "ION";
		$this->MODULE_DESCRIPTION = "Sources: github.com/amensum/ion";
		$this->MODULE_DIR = dirname(__DIR__);
	}

	/**
	 * @return bool
	 */
	public function InstallFiles(): bool
	{
		// Admin
		CopyDirFiles(
			$this->MODULE_DIR . "/install/admin",
			$_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin",
		);

		// Icons
		CopyDirFiles(
			$this->MODULE_DIR . "/install/themes/.default/icons/" . $this->MODULE_ID,
			$_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default/icons/" . $this->MODULE_ID,
		);

		// Styles
		CopyDirFiles(
			$this->MODULE_DIR . "/install/themes/.default",
			$_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default",
		);

		// Components
		CopyDirFiles(
			$this->MODULE_DIR . "/install/components/" . $this->MODULE_ID,
			$_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/" . $this->MODULE_ID,
            true,
            true
		);

		return true;
	}

	/**
	 * @return bool
	 */
	public function UnInstallFiles(): bool
	{
		// Admin
		DeleteDirFiles(
			$this->MODULE_DIR . "/install/admin",
			$_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin",
		);

		// Icons
		DeleteDirFiles(
			$this->MODULE_DIR . "/install/themes/.default/icons/" . $this->MODULE_ID,
			$_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default/icons/" . $this->MODULE_ID,
		);

		// Styles
		DeleteDirFiles(
			$this->MODULE_DIR . "/install/themes/.default",
			$_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default",
		);

        // Components
        DeleteDirFilesEx("/bitrix/components/" . $this->MODULE_ID);

		return true;
	}

	/**
	 * @return void
	 */
	public function DoInstall(): void
	{
		$this->InstallFiles();

		$eventManager = EventManager::getInstance();
		$eventManager->registerEventHandler("main", "OnPageStart", $this->MODULE_ID, Main::class, "getInstance");

		RegisterModule($this->MODULE_ID);
	}

	/**
	 * @return void
	 */
	public function DoUninstall(): void
	{
		$this->UnInstallFiles();

		$eventManager = EventManager::getInstance();
		$eventManager->unRegisterEventHandler("main", "OnPageStart", $this->MODULE_ID, Main::class, "getInstance");

		UnRegisterModule($this->MODULE_ID);
	}
}
