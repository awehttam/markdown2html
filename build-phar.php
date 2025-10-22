#!/usr/bin/env php
<?php

/**
 * Build script to create a PHAR archive of markdown2html
 */

$pharFile = 'markdown2html.phar';
$sourceDir = __DIR__;

// Check if phar.readonly is disabled
if (ini_get('phar.readonly')) {
    echo "Error: phar.readonly must be disabled in php.ini to create PHAR files.\n";
    echo "Run this script with: php -d phar.readonly=0 build-phar.php\n";
    exit(1);
}

// Remove existing PHAR if it exists
if (file_exists($pharFile)) {
    echo "Removing existing PHAR file...\n";
    unlink($pharFile);
}

try {
    echo "Creating PHAR archive...\n";

    $phar = new Phar($pharFile);
    $phar->startBuffering();

    // Add the main script
    echo "Adding markdown2html.php...\n";
    $phar->addFile('markdown2html.php', 'markdown2html.php');

    // Add vendor directory
    echo "Adding vendor directory...\n";
    $vendorDir = $sourceDir . DIRECTORY_SEPARATOR . 'vendor';

    if (!is_dir($vendorDir)) {
        echo "Error: vendor directory not found. Run 'composer install' first.\n";
        exit(1);
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($vendorDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    $fileCount = 0;
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $filePath = $file->getPathname();
            $relativePath = 'vendor' . DIRECTORY_SEPARATOR . substr($filePath, strlen($vendorDir) + 1);
            $phar->addFile($filePath, $relativePath);
            $fileCount++;

            if ($fileCount % 100 == 0) {
                echo "  Added {$fileCount} files...\n";
            }
        }
    }

    echo "Added {$fileCount} vendor files.\n";

    // Create a stub that will be executed when the PHAR is run
    $stub = <<<'STUB'
#!/usr/bin/env php
<?php
Phar::mapPhar('markdown2html.phar');
require 'phar://markdown2html.phar/markdown2html.php';
__HALT_COMPILER();
STUB;

    $phar->setStub($stub);
    $phar->stopBuffering();

    // Make it executable on Unix systems
    chmod($pharFile, 0755);

    echo "\nSuccess! PHAR archive created: {$pharFile}\n";
    echo "File size: " . number_format(filesize($pharFile)) . " bytes\n";
    echo "\nYou can now run it with:\n";
    echo "  php {$pharFile} [options] <input.md> [output.html]\n";
    echo "\nOr on Unix systems:\n";
    echo "  ./{$pharFile} [options] <input.md> [output.html]\n";

} catch (Exception $e) {
    echo "Error: Failed to create PHAR - " . $e->getMessage() . "\n";
    exit(1);
}
