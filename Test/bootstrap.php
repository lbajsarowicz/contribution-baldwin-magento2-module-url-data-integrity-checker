<?php

// took some inspiration from: https://magento.stackexchange.com/a/116987/2911

declare(strict_types=1);

use Magento\Framework\Code\Generator\Io;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\TestFramework\Unit\Autoloader\ExtensionAttributesGenerator;
use Magento\Framework\TestFramework\Unit\Autoloader\ExtensionAttributesInterfaceGenerator;
use Magento\Framework\TestFramework\Unit\Autoloader\FactoryGenerator;
use Magento\Framework\TestFramework\Unit\Autoloader\GeneratedClassesAutoloader;

$fileDriver = new File();
$generatedCodeDirectory = sys_get_temp_dir() . '/baldwin-generated-classes-' . uniqid('', true);
register_shutdown_function(function () use ($fileDriver, $generatedCodeDirectory): void {
    if ($fileDriver->isDirectory($generatedCodeDirectory)) {
        $fileDriver->deleteDirectory($generatedCodeDirectory);
    }
});
$generatorIo = new Io($fileDriver, $generatedCodeDirectory);
$generatedCodeAutoloader = new GeneratedClassesAutoloader(
    [
        new ExtensionAttributesGenerator(),
        new ExtensionAttributesInterfaceGenerator(),
        new FactoryGenerator(),
    ],
    $generatorIo
);
spl_autoload_register([$generatedCodeAutoloader, 'load']);
