<?php
/**
 * Database Migration Script
 * Run this script to create all database tables
 */

require_once 'app/config/config.php';
require_once 'app/core/Database.php';

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    echo "Starting database migration...\n";
    
    // Get all migration files
    $migrationFiles = glob(__DIR__ . '/migrations/*.sql');
    sort($migrationFiles);
    
    foreach ($migrationFiles as $file) {
        $filename = basename($file);
        echo "Running migration: {$filename}\n";
        
        $sql = file_get_contents($file);
        
        // Split SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $connection->exec($statement);
            }
        }
    }
    
    echo "Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
