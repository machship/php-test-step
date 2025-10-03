<?php

/**
 * PHP Step Essentials - Input/Output Helper Functions
 * Equivalent to the Go step-essentials/io library
 * 
 * Enhanced for Visual Go workflow engine compatibility.
 * This library handles all input parsing and output formatting,
 * maintaining repository integrity without external file injection.
 */

/**
 * GetInputs parses command line arguments and returns associative array
 * Supports both --inputs YAML and --inputs-file path formats
 * Compatible with Visual Go workflow engine input format
 */

// GetInputs() parses the --inputs YAML argument or --inputs-file and returns associative array
function GetInputs() {
    $options = getopt("", ["inputs:", "inputs-file:"]);
    $inputs = [];
    
    // Priority: file input over inline input (for large data)
    if (isset($options['inputs-file'])) {
        // Read inputs from file
        $inputsFile = $options['inputs-file'];
        if (file_exists($inputsFile)) {
            $yamlContent = file_get_contents($inputsFile);
            if ($yamlContent !== false) {
                $inputs = parseSimpleYAML($yamlContent);
            } else {
                echo "Error reading inputs file $inputsFile\n";
                return [];
            }
        } else {
            echo "Inputs file $inputsFile not found\n";
            return [];
        }
    } elseif (isset($options['inputs'])) {
        // Read inputs from inline YAML
        $yamlContent = $options['inputs'];
        $inputs = parseSimpleYAML($yamlContent);
    }
    
    return $inputs ?: [];
}

// Simple YAML parser for basic key-value pairs and arrays
function parseSimpleYAML($yamlContent) {
    $result = [];
    $lines = explode("\n", trim($yamlContent));
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        if (strpos($line, ':') !== false) {
            list($key, $value) = explode(':', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Handle arrays
            if (preg_match('/^\[(.*)\]$/', $value, $matches)) {
                $arrayContent = trim($matches[1]);
                if (!empty($arrayContent)) {
                    $elements = explode(',', $arrayContent);
                    $array = [];
                    foreach ($elements as $element) {
                        $element = trim($element, ' "\'');
                        if (is_numeric($element)) {
                            $array[] = strpos($element, '.') !== false ? (float)$element : (int)$element;
                        } else {
                            $array[] = $element;
                        }
                    }
                    $result[$key] = $array;
                } else {
                    $result[$key] = [];
                }
            } else {
                // Handle scalar values
                $value = trim($value, '"\'');
                if ($value === 'true') {
                    $result[$key] = true;
                } elseif ($value === 'false') {
                    $result[$key] = false;
                } elseif (is_numeric($value)) {
                    $result[$key] = strpos($value, '.') !== false ? (float)$value : (int)$value;
                } else {
                    $result[$key] = $value;
                }
            }
        }
    }
    
    return $result;
}

// SetOutputs() converts an associative array to YAML format and prints it as outputs
function SetOutputs($outputs) {
    echo "outputs:\n";
    foreach ($outputs as $key => $value) {
        if (is_array($value)) {
            // Handle arrays
            $arrayStr = '[';
            $first = true;
            foreach ($value as $item) {
                if (!$first) $arrayStr .= ', ';
                if (is_string($item)) {
                    $arrayStr .= '"' . addslashes($item) . '"';
                } else {
                    $arrayStr .= $item;
                }
                $first = false;
            }
            $arrayStr .= ']';
            echo "  $key: $arrayStr\n";
        } elseif (is_bool($value)) {
            echo "  $key: " . ($value ? 'true' : 'false') . "\n";
        } elseif (is_string($value)) {
            // Escape quotes and handle multiline strings
            if (strpos($value, "\n") !== false) {
                echo "  $key: |\n";
                $lines = explode("\n", $value);
                foreach ($lines as $line) {
                    echo "    $line\n";
                }
            } else {
                echo "  $key: \"" . addslashes($value) . "\"\n";
            }
        } else {
            echo "  $key: $value\n";
        }
    }
}
?>