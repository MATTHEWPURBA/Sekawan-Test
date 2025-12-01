<?php
/**
 * Database Configuration
 * 
 * This file contains sensitive database credentials.
 * Never commit this file to version control!
 * 
 * @version 2.0.0
 */

return [
    'db_host' => 'ep-soft-band-adq7jlul-pooler.c-2.us-east-1.aws.neon.tech',
    'db_port' => '5432',
    'db_name' => 'neondb',
    'db_user' => 'neondb_owner',
    'db_pass' => 'npg_qFrYv2eyG1UE',
    'db_ssl' => 'require',
    
    // Google Gemini API Configuration
    // Get your FREE API key from: https://makersuite.google.com/app/apikey
    // Leave empty or set to null to disable AI features
    'gemini_api_key' => 'AIzaSyDoUiiHMK2HC7czAIIMTRgtyWZFJuBJupY'  // Replace with your API key, e.g., 'AIza...'
];
