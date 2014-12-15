<?php

$vendorDir = is_dir(__DIR__ . '/vendor') ? __DIR__ . '/vendor' : __DIR__ . '/../../../../../vendor';

require_once $vendorDir . '/autoload.php';

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
