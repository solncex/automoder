<?php

include_once "params.php";

use Kernel\Kernel;

try{
    $kernel = new Kernel();
    $kernel->execution($_SERVER['REQUEST_URI']);
}catch(Exception $m){
    print "Ошибка " . $m->getCode() . ': ' .$m->getMessage() . "<br>";
    print "Файл " . $m->getFile() . ': ' .$m->getLine();
    print "<pre>";
    print $m->getTraceAsString();
    print "</pre>";
}
