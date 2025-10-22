# Markdown to HTML Converter

A simple command-line tool to convert Markdown files to HTML using PHP and the CommonMark library.

## Features

- Convert Markdown files to HTML or plain text format
- Based on the CommonMark specification
- Automatic output filename generation
- Input validation and error handling
- Safe HTML output (strips raw HTML input, blocks unsafe links)
- Command-line options for text output

## Requirements

- PHP 7.4 or higher
- Composer

## Installation

### Option 1: Standard Installation

1. Clone or download this repository
2. Install dependencies using Composer:

```bash
composer install
```

### Option 2: Using the PHAR Archive

If you want a single, self-contained executable file:

1. Download or build the `markdown2html.phar` file (see Building PHAR section below)
2. Use it directly without installing dependencies:

```bash
php markdown2html.phar input.md
```

On Unix systems, you can make it executable and run it directly:

```bash
chmod +x markdown2html.phar
./markdown2html.phar input.md
```

## Building PHAR

To create a standalone PHAR archive:

1. First, ensure dependencies are installed:

```bash
composer install
```

2. Build the PHAR file:

```bash
php -d phar.readonly=0 build-phar.php
```

This creates `markdown2html.phar` - a single file containing the application and all dependencies.

Note: The `-d phar.readonly=0` flag is required because PHP disables PHAR creation by default for security reasons.

## Usage

### Using the PHP script

Basic usage with automatic output filename:

```bash
php markdown2html.php input.md
```

This will create `input.html` in the same directory as the input file.

Specify a custom output filename:

```bash
php markdown2html.php input.md output.html
```

Convert to plain text instead of HTML:

```bash
php markdown2html.php --text input.md
# or use the short option:
php markdown2html.php -t input.md
```

This will create `input.txt` with plain text output (HTML tags stripped).

### Using the PHAR archive

The PHAR archive works identically to the PHP script:

```bash
php markdown2html.phar input.md
php markdown2html.phar input.md output.html
php markdown2html.phar --text input.md
```

On Unix systems with executable permissions:

```bash
./markdown2html.phar input.md
./markdown2html.phar -t input.md output.txt
```

### Examples

Convert a README file to HTML:
```bash
php markdown2html.php README.md
# Creates README.html
```

Convert with custom output path:
```bash
php markdown2html.php docs/guide.md public/guide.html
```

Convert to plain text:
```bash
php markdown2html.php --text README.md
# Creates README.txt
```

Convert to plain text with custom output:
```bash
php markdown2html.php -t docs/guide.md output/guide.txt
```

## Error Handling

The tool includes comprehensive error checking for:
- Missing or invalid input files
- Unreadable input files
- Write permission issues
- Conversion errors

## Dependencies

- [league/commonmark](https://github.com/thephpleague/commonmark) ^2.7 - A Markdown parser for PHP based on the CommonMark specification

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
