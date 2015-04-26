<?php

namespace aeqdev\afw;

class InstanceFactory
{

    private static $instances = [];

    protected static function instance($__FUNCTION__, $instance)
    {
        if (!isset(self::$instances[$__FUNCTION__]))
        {
            if (array_key_exists($__FUNCTION__, self::$instances))
            {
                throw new \Exception("instance '$__FUNCTION__' is null or not yet created");
            }
            $instance(self::$instances[$__FUNCTION__]);
        }
        return self::$instances[$__FUNCTION__];
    }

}
