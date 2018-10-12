<?php

namespace SfCQRSDemo\Shared;

class ClassNameHelper
{
    /**
     * Copy from Symfony maker str
     *
     * @param $fullClassName
     *
     * @return string
     */
    public static function getShortClassName($fullClassName): string
    {
        if (empty(self::getNamespace($fullClassName))) {
            return $fullClassName;
        }

        return substr($fullClassName, strrpos($fullClassName, '\\') + 1);
    }

    /**
     * Copy from Symfony maker str
     *
     * @param string $fullClassName
     *
     * @return string
     */
    public static function getNamespace(string $fullClassName): string
    {
        return substr($fullClassName, 0, strrpos($fullClassName, '\\'));
    }
}
