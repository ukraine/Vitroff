<?php

/**
 * Class Autoload
 * автозагрузка классов vitroff
 *
 * @author Vladimir Chmil <vladimir.chmil@gmail.com>
 */

class Autoload
{

    public function __construct()
    {
        spl_autoload_register(array('self', 'vitroff_autoloader'));
    }

    /**
     * autoload. Search "lib/classes" recursively
     *
     * @param string $class class name
     *
     * @return nothing
     */
    private static function vitroff_autoloader($class)
    {
        foreach (
        new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
        CLASS_PATH
        )
        ) as $x
        ) {
            if (stripos($x->getPathname(), $class) !== false && is_dir($x->getPathname()) === false
            ) {
                require_once $x->getPathname();
            }
        }
    }

}

