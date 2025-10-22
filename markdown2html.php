#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use League\CommonMark\CommonMarkConverter;

// Parse command-line options
$options = getopt('t', ['text']);
$outputAsText = isset($options['t']) || isset($options['text']);

// Ensure $argv is available
global $argv;
if (!isset($argv)) {
    $argv = $_SERVER['argv'] ?? [];
}

// Remove options from argv to get positional arguments
$args = array_values(array_filter($argv, function($arg) use ($argv) {
    return $arg !== '-t' && $arg !== '--text' && $arg !== $argv[0];
}));

// Check if the correct number of arguments is provided
if (count($args) < 1) {
    echo "Usage: php markdown2html.php [options] <input.md> [output.html|output.txt]\n";
    echo "Options:\n";
    echo "  -t, --text  - Output as plain text instead of HTML\n";
    echo "\n";
    echo "Arguments:\n";
    echo "  input.md    - Path to the Markdown file to convert\n";
    echo "  output.html - (Optional) Path to the output file\n";
    echo "                If not specified, will use input filename with\n";
    echo "                .html extension (or .txt with --text option)\n";
    exit(1);
}

$inputFile = $args[0];
$outputFile = $args[1] ?? null;

// Check if input file exists
if (!file_exists($inputFile)) {
    echo "Error: Input file '{$inputFile}' not found.\n";
    exit(1);
}

// Check if input file is readable
if (!is_readable($inputFile)) {
    echo "Error: Input file '{$inputFile}' is not readable.\n";
    exit(1);
}

// If output file is not specified, generate it from input filename
if ($outputFile === null) {
    $pathInfo = pathinfo($inputFile);
    $extension = $outputAsText ? '.txt' : '.html';
    $outputFile = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['filename'] . $extension;
}

// Read the Markdown file
$markdown = file_get_contents($inputFile);

if ($markdown === false) {
    echo "Error: Failed to read input file '{$inputFile}'.\n";
    exit(1);
}

// Convert Markdown to HTML using CommonMark
$converter = new CommonMarkConverter([
    'html_input' => 'strip',
    'allow_unsafe_links' => false,
]);

try {
    $html = $converter->convert($markdown);

    // If text output is requested, strip HTML tags and decode HTML entities
    if ($outputAsText) {
        $output = strip_tags($html);
        $output = html_entity_decode($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    } else {
        $output = $html;
    }

    // Write the output to the file
    $result = file_put_contents($outputFile, $output);

    if ($result === false) {
        echo "Error: Failed to write to output file '{$outputFile}'.\n";
        exit(1);
    }

    $format = $outputAsText ? 'text' : 'HTML';
    echo "Successfully converted '{$inputFile}' to '{$outputFile}' ({$format} format).\n";
    exit(0);

} catch (Exception $e) {
    echo "Error: Conversion failed - " . $e->getMessage() . "\n";
    exit(1);
}
