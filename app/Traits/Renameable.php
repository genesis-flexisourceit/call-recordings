<?php
namespace App\Traits;

trait Renameable
{
    /**
     * @param $originalName
     *
     * @return bool|string
     */
    protected function removeExtension($originalName)
    {
       return substr($originalName, 0, strrpos($originalName, '.'));
    }
}
