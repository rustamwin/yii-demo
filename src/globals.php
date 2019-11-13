<?php

use Yiisoft\VarDumper\VarDumper;

if (!function_exists('dd')) {
   function dd(...$variables) {
       foreach ($variables as $dumpVariable) {
           VarDumper::dump($dumpVariable, 10, true);
       }

       die;
   }
}
if (!function_exists('dump')) {
    function dump($value): ?string
    {
        VarDumper::dump($value);
        die;
    }
}