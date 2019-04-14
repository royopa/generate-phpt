<?php

/**
 * This creates a standalone phar file with all of the PHP source included. To run the
 * phar just type 'php generate-phpt.phar <options>' at the command line.
 */

if (Phar::canWrite()) {
	echo "Writing phar archive\n";
} else {
 	echo "Unable to write archive, check that phar.readonly is 0 in your php.ini\n";
 	exit();
}

$thisDir = dirname(__FILE__);

$pharPath = $thisDir . DIRECTORY_SEPARATOR . 'build';

if (!file_exists($pharPath)) {
    mkdir($pharPath);
}

$pharFile = 'generate-phpt.phar';

$phar = new Phar($pharPath . DIRECTORY_SEPARATOR . $pharFile);

$phar->buildFromDirectory($thisDir . DIRECTORY_SEPARATOR . 'src');

$stub = <<<ENDSTUB
<?php
Phar::mapPhar('generate-phpt.phar');
require 'phar://generate-phpt.phar/generate-phpt.php';
__HALT_COMPILER();
ENDSTUB;

$phar->setStub($stub);

# Make the file executable
chmod($pharPath . DIRECTORY_SEPARATOR . $pharFile, 0770);

echo "$pharFile successfully created on build folder" . PHP_EOL;
