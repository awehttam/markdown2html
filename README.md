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

1. Clone or download this repository
2. Install dependencies using Composer:

```bash
composer install
```

## Usage

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

This project is open source.
