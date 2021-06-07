<?php
require_once('secplugs.class.php');

$secplugs = new Secplugs();

// Test eicar
$data = base64_decode("WDVPIVAlQEFQWzRcUFpYNTQoUF4pN0NDKTd9JEVJQ0FSLVNUQU5EQVJELUFOVElWSVJVUy1URVNULUZJTEUhJEgrSCo=");
$eicar_file = "/tmp/eicar.com";
file_put_contents($eicar_file, $data);
$res = $secplugs->isClean($eicar_file);
unlink($eicar_file);
if ($res) {
    exit(1);
}

// Test clean file
$clean_file = $argv[0];
$res = $secplugs->isClean($clean_file);
if (!$res) {
    exit(1);
}

?>
    
