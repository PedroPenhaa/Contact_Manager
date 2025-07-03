<?php

$input = file_get_contents('contatos_ficticios.csv');
$lines = explode("\n", $input);

// Remove empty lines
$lines = array_filter($lines);

// Convert to the correct format
$output = [];
foreach ($lines as $i => $line) {
    if ($i === 0) {
        // Convert header
        $output[] = "name,email,phone";
        continue;
    }
    
    // Split by tabs or multiple spaces
    $fields = preg_split('/\s+/', $line, 3);
    if (count($fields) === 3) {
        $name = trim($fields[0]);
        $email = trim($fields[1]);
        $phone = trim($fields[2]);
        $output[] = "$name,$email,$phone";
    }
}

// Save the converted file
file_put_contents('contacts_import.csv', implode("\n", $output)); 