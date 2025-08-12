<?php

declare (strict_types=1);
namespace RectorPrefix202504;

use Rector\Config\RectorConfig;
use Rector\Doctrine\Collection22\Rector\CriteriaOrderingConstantsDeprecationRector;
return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->rules([CriteriaOrderingConstantsDeprecationRector::class]);
};
