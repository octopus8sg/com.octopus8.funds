<?php

use CRM_Funds_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Funds_Upgrader extends CRM_Funds_Upgrader_Base
{

    // By convention, functions that look like "function upgrade_NNNN()" are
    // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

    /**
     * Example: Run an external SQL script when the module is installed.
     *
     */
    public function install()
    {
        $this->uninstall();
        // Create the device_type and sensor option groups
        $trxnStatusGroup = civicrm_api3('OptionGroup',
            'create',
            ['name' => 'o8_fund_trxn_status',
                'title' => E::ts('Fund Transaction Status')]);
        $trxnStatusGroupId = $trxnStatusGroup['id'];

        $device_type1 = civicrm_api3('OptionValue', 'create',
            ['value' => CRM_Funds_BAO_FundTransaction::PENDING_APPROVAL,
                'is_default' => '1',
                'name' => 'Pending_Approval',
                'label' => E::ts('Pending Approval'),
                'option_group_id' => $trxnStatusGroupId]);
        civicrm_api3('OptionValue', 'create',
            ['value' => CRM_Funds_BAO_FundTransaction::APPROVED,
                'name' => 'Approved',
                'label' => E::ts('Approved'),
                'option_group_id' => $trxnStatusGroupId]);
        civicrm_api3('OptionValue', 'create',
            ['value' => CRM_Funds_BAO_FundTransaction::REJECTED,
                'name' => 'Rejected',
                'label' => E::ts('Rejected'),
                'option_group_id' => $trxnStatusGroupId]);
    }

    /**
     * Example: Work with entities usually not available during the install step.
     *
     * This method can be used for any post-install tasks. For example, if a step
     * of your installation depends on accessing an entity that is itself
     * created during the installation (e.g., a setting or a managed entity), do
     * so here to avoid order of operation problems.
     */
    function postInstall() {
        // Update schemaVersion if added new version in upgrade process.
        CRM_Core_BAO_Extension::setSchemaVersion('com.octopus8.funds', 10006);
    }

    /**
     * Example: Run an external SQL script when the module is uninstalled.
     */
    public function uninstall()
    {
        try {
            $optionGroupId = civicrm_api3('OptionGroup',
                'getvalue', ['return' => 'id',
                    'name' => 'o8_fund_trxn_status']);
            $optionValues = civicrm_api3('OptionValue',
                'get', ['option_group_id' => $optionGroupId, 'options' => ['limit' => 0]]);
            foreach ($optionValues['values'] as $optionValue) {
                civicrm_api3('OptionValue', 'delete', ['id' => $optionValue['id']]);
            }
            civicrm_api3('OptionGroup', 'delete', ['id' => $optionGroupId]);
        } catch (\CiviCRM_API3_Exception $ex) {
            // Ignore exception.
        }

    }

    /**
     * Example: Run a simple query when a module is enabled.
     * @throws Exception
     */
     public function enable() {
         CRM_Core_BAO_Extension::setSchemaVersion('com.octopus8.funds', 10006);
         $revision =  $this->getCurrentRevision();
         if($revision >= 10006){
             $this->upgrade_10006();
         }
         CRM_Core_Error::debug_var('revision', $this->getCurrentRevision());

         //      CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
     }

    /**
     * Example: Run a simple query when a module is disabled.
     */
    // public function disable() {
    //   CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
    // }

    /**
     * Example: Run a couple simple queries.
     *
     * @return TRUE on success
     * @throws Exception
     */
    // public function upgrade_4200(): bool {
    //   $this->ctx->log->info('Applying update 4200');
    //   CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
    //   CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
    //   return TRUE;
    // }


    /**
     * Example: Run an external SQL script.
     *
     * @return TRUE on success
     * @throws Exception
     */
    // public function upgrade_4201(): bool {
    //   $this->ctx->log->info('Applying update 4201');
    //   // this path is relative to the extension base dir
    //   $this->executeSqlFile('sql/upgrade_4201.sql');
    //   return TRUE;
    // }


    /**
     * Example: Run a slow upgrade process by breaking it up into smaller chunk.
     *
     * @return TRUE on success
     * @throws Exception
     */
    // public function upgrade_4202(): bool {
    //   $this->ctx->log->info('Planning update 4202'); // PEAR Log interface

    //   $this->addTask(E::ts('Process first step'), 'processPart1', $arg1, $arg2);
    //   $this->addTask(E::ts('Process second step'), 'processPart2', $arg3, $arg4);
    //   $this->addTask(E::ts('Process second step'), 'processPart3', $arg5);
    //   return TRUE;
    // }
    // public function processPart1($arg1, $arg2) { sleep(10); return TRUE; }
    // public function processPart2($arg3, $arg4) { sleep(10); return TRUE; }
    // public function processPart3($arg5) { sleep(10); return TRUE; }

    /**
     * Example: Run an upgrade with a query that touches many (potentially
     * millions) of records by breaking it up into smaller chunks.
     *
     * @return TRUE on success
     * @throws Exception
     */
    // public function upgrade_4203(): bool {
    //   $this->ctx->log->info('Planning update 4203'); // PEAR Log interface

    //   $minId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(min(id),0) FROM civicrm_contribution');
    //   $maxId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(max(id),0) FROM civicrm_contribution');
    //   for ($startId = $minId; $startId <= $maxId; $startId += self::BATCH_SIZE) {
    //     $endId = $startId + self::BATCH_SIZE - 1;
    //     $title = E::ts('Upgrade Batch (%1 => %2)', array(
    //       1 => $startId,
    //       2 => $endId,
    //     ));
    //     $sql = '
    //       UPDATE civicrm_contribution SET foobar = whiz(wonky()+wanker)
    //       WHERE id BETWEEN %1 and %2
    //     ';
    //     $params = array(
    //       1 => array($startId, 'Integer'),
    //       2 => array($endId, 'Integer'),
    //     );
    //     $this->addTask($title, 'executeSql', $sql, $params);
    //   }
    //   return TRUE;
    // }

    /**
     * @return TRUE on success
     * @throws Exception
     * 1 - remove table
     * 2 - remove row
     * 3 - remove foreign index
     */
    public function upgrade_10006()
    {
        CRM_Core_Error::debug_var('news', 'Applying update 0006 - Remove civicrm_o8_fund_account_type table');
//        $this->ctx->log->info('Applying update 0006 - Remove civicrm_o8_fund_account_type table');
        $tableToRemove = 'civicrm_o8_fund_account_type';
        $tableToRemoveColumn = 'civicrm_o8_fund_account';
        $columnToRemove = 'type_id';
        $foreinKeyToRemove = 'FK_civicrm_o8_fund_account_type_id';
        if (self::checkColumnExists('civicrm_o8_fund_account', 'type_id')) {
//            CRM_Core_Error::debug_var('columnToRemove', $columnToRemove);
            // There is no such column in new version
            try {
//                CRM_Core_Error::debug_var('fkToRemove', $columnToRemove);
                CRM_Core_DAO::executeQuery("ALTER TABLE `$tableToRemoveColumn` DROP FOREIGN KEY `$foreinKeyToRemove`;");
            } catch (Exception $e) {
                CRM_Core_Error::debug_var('error1', $e->getMessage());
            }
            try {
//                CRM_Core_Error::debug_var('columnToRemove', $columnToRemove);
                CRM_Core_DAO::executeQuery("ALTER TABLE `$tableToRemoveColumn` DROP `$columnToRemove`;");
            } catch (Exception $e) {
                CRM_Core_Error::debug_var('error2', $e->getMessage());
            }
            // There is no such column in new version
            return TRUE;
        }
        if (CRM_Core_DAO::checkTableExists('civicrm_o8_fund_account_type')) {
            // There is no such table in new version
            try {
//                CRM_Core_Error::debug_var('tableToRemove', $tableToRemove);
                CRM_Core_DAO::executeQuery("DROP TABLE IF EXISTS $tableToRemove;");
            } catch (Exception $e) {
                CRM_Core_Error::debug_var('error3', $e->getMessage());
            }
            return TRUE;
        }
        return TRUE;
    }

    /**
     * Check if there is a given table in the database.
     *
     * @param string $tableName
     *
     * @return bool
     *   true if exists, else false
     */
    public static function checkColumnExists($tableName, $columnName)
    {
        $query = "
SHOW COLUMNS from `%1` LIKE %2;
";
        $params = [
            1 => [$tableName, 'MysqlColumnNameOrAlias'],
            2 => [$columnName, 'String'],
        ];

        $dao = CRM_Core_DAO::executeQuery($query, $params);
        return (bool)$dao->fetch();
    }


}