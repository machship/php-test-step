<?php

/**
 * Utility functions for PHP repository processing
 * Equivalent to utility functions that might be in utils.go or similar files
 */

/**
 * Get comprehensive processing statistics
 */
function getProcessingStats($originalNumbers, $processedNumbers, $factor) {
    $originalSum = array_sum($originalNumbers);
    $processedSum = array_sum($processedNumbers);
    
    return [
        'original_count' => count($originalNumbers),
        'processed_count' => count($processedNumbers),
        'factor_used' => $factor,
        'original_sum' => $originalSum,
        'processed_sum' => $processedSum,
        'enhancement_applied' => $processedSum - ($originalSum * $factor),
        'processing_time' => microtime(true)
    ];
}

/**
 * Format processing information into a readable string
 */
function formatProcessingInfo($stats) {
    $lines = [
        "PHP Repository Processing Report:",
        "- Original numbers: {$stats['original_count']}",
        "- Factor applied: {$stats['factor_used']}",
        "- Original sum: {$stats['original_sum']}",
        "- Processed sum: {$stats['processed_sum']}",
        "- Enhancement bonus: +{$stats['enhancement_applied']}",
        "- Processing: SUCCESSFUL"
    ];
    
    return implode(' | ', $lines);
}

/**
 * Calculate array statistics
 */
function calculateArrayStats($numbers) {
    if (empty($numbers)) {
        return [
            'min' => 0,
            'max' => 0,
            'average' => 0,
            'sum' => 0,
            'count' => 0
        ];
    }
    
    return [
        'min' => min($numbers),
        'max' => max($numbers),
        'average' => array_sum($numbers) / count($numbers),
        'sum' => array_sum($numbers),
        'count' => count($numbers)
    ];
}

/**
 * Log processing step with timestamp
 */
function logProcessingStep($step, $data = null) {
    $timestamp = date('Y-m-d H:i:s');
    $message = "[$timestamp] $step";
    
    if ($data !== null) {
        if (is_array($data)) {
            $message .= " - Data: " . json_encode($data);
        } else {
            $message .= " - Data: $data";
        }
    }
    
    echo $message . "\n";
}

/**
 * Validate processing inputs
 */
function validateInputs($inputs) {
    $required = ['numbers', 'factor'];
    
    foreach ($required as $field) {
        if (!isset($inputs[$field])) {
            throw new InvalidArgumentException("Missing required input: $field");
        }
    }
    
    if (!is_array($inputs['numbers'])) {
        throw new InvalidArgumentException('Numbers must be an array');
    }
    
    if (!is_numeric($inputs['factor'])) {
        throw new InvalidArgumentException('Factor must be numeric');
    }
    
    return true;
}

/**
 * Format execution time
 */
function formatExecutionTime($startTime) {
    $endTime = microtime(true);
    $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
    return round($executionTime, 2) . 'ms';
}

?>