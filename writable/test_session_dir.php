<?php
// Test script to check session directory permissions
echo "Testing session directory permissions...\n";

$sessionPath = __DIR__ . '/session';

echo "Session path: $sessionPath\n";

// Check if directory exists
if (!is_dir($sessionPath)) {
    echo "Directory does not exist. Creating...\n";
    if (mkdir($sessionPath, 0700, true)) {
        echo "Directory created successfully.\n";
    } else {
        die("Failed to create directory. Please check permissions.\n");
    }
} else {
    echo "Directory exists.\n";
}

// Test writing a file
$testFile = $sessionPath . '/test.txt';
echo "Testing write permissions...\n";
if (file_put_contents($testFile, 'test') !== false) {
    echo "Successfully wrote to test file.\n";
    unlink($testFile); // Clean up
} else {
    echo "Failed to write to test file.\n";
}

echo "Done.\n";
