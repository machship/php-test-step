<?php

require_once 'input.php';
require_once 'processor.php';
require_once 'utils.php';

function main() {
    // Get inputs from the workflow engine
    $inputs = GetInputs();
    
    // Extract numbers and factor from inputs
    $numbers = $inputs['numbers'] ?? [];
    $factor = $inputs['factor'] ?? 1;
    
    // Log the inputs for debugging
    echo "Processing " . count($numbers) . " numbers with factor $factor\n";
    
    // Use the processor library to handle the computation
    $processor = new NumberProcessor($factor);
    $processedNumbers = $processor->processNumbers($numbers);
    
    // Get processing statistics using utility functions
    $stats = getProcessingStats($numbers, $processedNumbers, $factor);
    $processingInfo = formatProcessingInfo($stats);
    
    // Log processing results
    echo "Processed results: " . implode(', ', $processedNumbers) . "\n";
    echo "Processing info: $processingInfo\n";
    
    // Set outputs for the workflow engine
    SetOutputs([
        'processed_numbers' => $processedNumbers,
        'processing_info' => $processingInfo
    ]);
}

// Execute main function
main();

?>