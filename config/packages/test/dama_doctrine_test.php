<?php

use Symfony\Config\DamaDoctrineTestConfig;

return static function (DamaDoctrineTestConfig $damaDoctrineTest): void {
    $damaDoctrineTest->enableStaticConnection(true)->enableStaticMetaDataCache(true)->enableStaticQueryCache(true);
};
