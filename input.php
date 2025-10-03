<?php

/**
 * PHP Step Essentials - Input/Output Helper Functions
 * Equivalent to the Go step-essentials/io library
 */

/**
 * GetInputs parses command line arguments and returns associative array
 * Supports both --inputs YAML and --inputs-file path formats
 */
function GetInputs() {
    global $argv;
    
    $inputs = [];
    
    for ($i = 1; $i < count($argv); $i++) {
        if ($argv[$i] === '--inputs' && isset($argv[$i + 1])) {
            // Parse YAML from command line argument
            $yamlContent = $argv[$i + 1];
            $inputs = parseSimpleYaml($yamlContent);
            break;
        } elseif ($argv[$i] === '--inputs-file' && isset($argv[$i + 1])) {
            // Read and parse YAML from file
            $filePath = $argv[$i + 1];
            if (file_exists($filePath)) {
                $yamlContent = file_get_contents($filePath);
                $inputs = parseSimpleYaml($yamlContent);
            }
            break;
        }
    }
    
    return $inputs;
}

/**
 * SetOutputs formats associative array as YAML and outputs to stdout
 * This output is captured by the workflow engine
 */
function SetOutputs($outputs) {
    echo formatAsYaml($outputs);
}

/**
 * Simple YAML parser for basic key-value pairs and arrays
 * Handles the common patterns used in workflow inputs/outputs
 */
function parseSimpleYaml($yamlContent) {
    $result = [];
    $lines = explode("\n", trim($yamlContent));
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        
        if (strpos($line, ':') !== false) {
            // Split only on the first colon to handle array values correctly
            $colonPos = strpos($line, ':');
            $key = trim(substr($line, 0, $colonPos));
            $value = trim(substr($line, $colonPos + 1));
            
            // Handle arrays in YAML format [1, 2, 3]
            if (preg_match('/^\[(.*?)\]$/', $value, $matches)) {
                $arrayStr = $matches[1];
                if (!empty($arrayStr)) {
                    $result[$key] = array_map(function($v) {
                        $v = trim($v);
                        return is_numeric($v) ? (strpos($v, '.') !== false ? floatval($v) : intval($v)) : $v;
                    }, explode(',', $arrayStr));
                } else {
                    $result[$key] = [];
                }
            }
            // Handle numeric values
            elseif (is_numeric($value)) {
                $result[$key] = strpos($value, '.') !== false ? floatval($value) : intval($value);
            }
            // Handle boolean values
            elseif (in_array(strtolower($value), ['true', 'false'])) {
                $result[$key] = strtolower($value) === 'true';
            }
            // Handle string values (remove quotes if present)
            else {
                $result[$key] = trim($value, '"\'\'');
            }
        }
    }
    
    return $result;
}

/**
 * Format associative array as YAML output
 * Generates YAML format that the workflow engine can parse
 */
function formatAsYaml($data) {
    $yaml = "";
    
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            // Format arrays as [1, 2, 3]
            $arrayStr = '[' . implode(', ', $value) . ']';
            $yaml .= "$key: $arrayStr\n";
        } elseif (is_bool($value)) {
            $yaml .= "$key: " . ($value ? 'true' : 'false') . "\n";
        } elseif (is_string($value)) {
            // Escape strings that contain special characters
            if (preg_match('/[:\n\r\t]/', $value)) {
                $escaped = addslashes($value);
                $yaml .= "$key: \"$escaped\"\n";
            } else {
                $yaml .= "$key: $value\n";
            }
        } else {
            $yaml .= "$key: $value\n";
        }
    }
    
    return $yaml;
}

?>