<?php
/**
 * Database Seeding Script
 * Run this script to populate the database with sample data
 */

require_once 'app/config/config.php';
require_once 'app/core/Database.php';

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    echo "Starting database seeding...\n";
    
    // Get all seed files
    $seedFiles = glob(__DIR__ . '/seeds/*.sql');
    sort($seedFiles);
    
    foreach ($seedFiles as $file) {
        $filename = basename($file);
        echo "Running seed: {$filename}\n";
        
        $sql = file_get_contents($file);
        
        // Split SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $connection->exec($statement);
            }
        }
    }
    
    echo "Seeding completed successfully!\n";
    
} catch (Exception $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}
