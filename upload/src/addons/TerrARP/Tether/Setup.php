<?php

namespace TerrARP\Tether;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    /**
     * This method is called when the add-on is installed.
     * Since we're avoiding database operations, this is minimal.
     */
    public function installStep1()
    {
        // No database operations needed
        // BBCode will be configured manually via Admin CP
    }

    /**
     * This method is called when the add-on is uninstalled.
     */
    public function uninstallStep1()
    {
        // Note: Manual BBCode entries should be removed via Admin CP
    }
}
