<?php

/**
 * NumberProcessor - Core processing logic for numerical operations
 * Equivalent to business logic that might be in separate Go files
 */
class NumberProcessor {
    private $factor;
    private $processingStartTime;
    
    public function __construct($factor = 1) {
        $this->factor = $factor;
        $this->processingStartTime = microtime(true);
    }
    
    /**
     * Process an array of numbers by applying the factor
     * Implements the core business logic for the PHP repository step
     */
    public function processNumbers($numbers) {
        if (!is_array($numbers)) {
            throw new InvalidArgumentException('Numbers must be an array');
        }
        
        $processed = [];
        
        foreach ($numbers as $number) {
            // Apply the factor multiplication
            $result = $this->applyProcessing($number);
            $processed[] = $result;
        }
        
        return $processed;
    }
    
    /**
     * Apply processing to a single number
     * This could be extended with more complex mathematical operations
     */
    private function applyProcessing($number) {
        // Apply factor multiplication with some additional processing
        $base = $number * $this->factor;
        
        // Add some mathematical enhancement (similar to what the Go version might do)
        $enhanced = $base + ($number % 2); // Add 1 for odd numbers, 0 for even
        
        return $enhanced;
    }
    
    /**
     * Get processing statistics
     */
    public function getProcessingTime() {
        return microtime(true) - $this->processingStartTime;
    }
    
    /**
     * Get the factor used for processing
     */
    public function getFactor() {
        return $this->factor;
    }
    
    /**
     * Validate input numbers
     */
    public function validateNumbers($numbers) {
        if (!is_array($numbers)) {
            return false;
        }
        
        foreach ($numbers as $number) {
            if (!is_numeric($number)) {
                return false;
            }
        }
        
        return true;
    }
}

?>