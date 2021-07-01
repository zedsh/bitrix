<?php


namespace App\Helpers;


use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;

class DbLockHelper
{
    const CONNECTION = 'default';
    const TIMEOUT = 5;

    public static function lock($lockName)
    {
        $connection = Application::getInstance()->getConnectionPool()->getConnection(static::CONNECTION);
        if ($connection instanceof \Bitrix\Main\DB\MysqlCommonConnection) {
            $lock = $connection->queryScalar(
                sprintf('SELECT GET_LOCK("%s", %d)', $lockName, static::TIMEOUT)
            );
            return ($lock != '0');

        } else {
            throw new \Exception('Unsupported db');
        }
    }


    public static function unlock($lockName)
    {
        $connection = Application::getInstance()->getConnectionPool()->getConnection(static::CONNECTION);
        if ($connection instanceof \Bitrix\Main\DB\MysqlCommonConnection) {
            $connection->queryExecute(
				sprintf('DO RELEASE_LOCK("%s")', $lockName)
			);
            return true;
        } else {
            throw new \Exception('Unsupported db');
        }
    }
}
