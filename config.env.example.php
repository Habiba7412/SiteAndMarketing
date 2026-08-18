<?php
/**
 * Example Configuration File
 * 
 * Instructions:
 * 1. Copy this file and rename it to 'config.env.php' on your live server.
 * 2. Update the credentials below to match your live cPanel/MySQL settings.
 * 3. DO NOT commit 'config.env.php' to Git. It is ignored in .gitignore.
 */

return [
    'DB_HOST' => 'localhost',
    'DB_USER' => 'YOUR_CPANEL_USERNAME',
    'DB_PASS' => 'YOUR_CPANEL_PASSWORD',
    'DB_NAME' => 'YOUR_CPANEL_DBNAME',
    
    // Set to false in production to hide sensitive errors
    'DISPLAY_ERRORS' => false,
    
    // Base URL of the live site (used as a fallback)
    'BASE_URL' => 'https://siteandmarketing.com',
];
