```
<?php
require_once(__DIR__.'/vendor/autoload.php');
use Secplugs\FileScan;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Normal code to get the file from POST request
    // Then integrage with Secplugs
    $filescan = new FileScan();
    if ($filescan->isClean($uploadedFile)) {
        echo 'Clean file';
        // Existing flow to handle file upload
    } else {
        echo 'Malicious file';
        // Code for handling upload error
    }
}
?>

```
