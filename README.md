# PHP Test Step

A PHP repository for Visual Go workflow automation, equivalent to the Go test-step repository.

## Structure

- `main.php` - Entry point for the step execution
- `input.php` - Input/Output helper functions (equivalent to Go's step-essentials/io)
- `processor.php` - Core business logic for number processing
- `utils.php` - Utility functions for statistics and formatting
- `composer.json` - PHP package configuration

## Usage

This repository is designed to be used as an external repository step in Visual Go workflows.

### Input Format

The step expects inputs in YAML format:

```yaml
numbers: [5, 10, 15, 20, 25, 30]
factor: 3
```

### Output Format

The step produces outputs in YAML format:

```yaml
processed_numbers: [16, 31, 46, 61, 76, 91]
processing_info: "PHP Repository Processing Report: - Original numbers: 6 | Factor applied: 3 | Original sum: 105 | Processed sum: 321 | Enhancement bonus: +6 | Processing: SUCCESSFUL"
```

### Processing Logic

1. Multiplies each number by the provided factor
2. Adds enhancement bonus (1 for odd numbers, 0 for even numbers)
3. Provides detailed processing statistics

### Example Execution

```bash
php main.php --inputs "numbers: [5, 10, 15] factor: 2"
```

## Dependencies

- PHP >= 7.4
- No external dependencies (uses built-in PHP functions only)

## Integration

Designed to work with the Visual Go workflow engine's PHP language support. The `input.php` file provides `GetInputs()` and `SetOutputs()` functions that are compatible with the workflow engine's input/output format.

## File Structure

```
php-test-step/
├── main.php           # Entry point
├── input.php          # I/O helpers
├── processor.php      # Business logic
├── utils.php          # Utilities
├── composer.json      # Package config
└── README.md          # Documentation
```

This structure mirrors the Go test-step repository but adapted for PHP execution within the Visual Go workflow system.
