<?php
/**
 * Ioc.php
 * 文件描述
 * Created on 2023/11/16 11:48
 * Creat by ClearSwitch
 */

namespace ClearSwitch\BleachHttp\Container;

class Ioc
{
    /**
     * 服务容器
     * @var array
     */
    public static $ServerContainer = [];

    /**
     * 绑定服务
     * @param $className
     * @param $closure_obj
     * @author clearSwitch
     */
    public static function bind($className, $closure_obj)
    {
        if ($closure_obj instanceof \Closure) {
            static::$ServerContainer[$className] = call_user_func($closure_obj);
        } else {
            static::$ServerContainer[$className] = static::getInstances($closure_obj);
        }
    }

    /**
     * 使用服务
     * @param $className
     * @param $closure_obj
     * @return mixed
     * @throws \Exception
     * @author clearSwitch
     */
    public static function make($className, $closure_obj = null)
    {
        if (empty(static::$ServerContainer[$className])) {
            static::$ServerContainer[$className] = static::getInstances($closure_obj);
        }
        return static::$ServerContainer[$className];
    }

    /**
     * 依赖注入
     * @param $className
     * @author clearSwitch
     */
    public static function getInstances($className)
    {
        //获得类的所有信息
        try {
            $ref = new \ReflectionClass($className);
        } catch (\Exception $re) {
            throw new \Exception("没有项目中没有这个文件");
        }
        if ($ref->isInstantiable()) {
            $constructor = $ref->getConstructor();
            if ($constructor) {
                //获得构造函数的参数
                $pars = $constructor->getParameters();
                if (!empty($pars)) {
                    $dependencies = null;
                    foreach ($pars as $par) {
                        $dependencyClass = $par->getClass();
                        if (is_null($dependencyClass)) {
                            $dependencies[] = null;
                        } else {
                            $dependencies[] = static::make($par->getClass()->name);
                        }
                    }
                    return $ref->newInstanceArgs($dependencies);
                } else {
                    return new $className;
                }
            } else {
                return new $className;
            }
        } else {
            throw new \Exception("传入的类是不可以实例的");
        }
    }
}