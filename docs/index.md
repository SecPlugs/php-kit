---
layout: none
---

# PHP Kit
A {brand-name} powered, ready to use PHP class and examples.

The scripts are up open source on GitHub and contain ample comments, so you can fork them and modify as you wish.

Also see [PHP Kit Listing](/plugin-list/plugin-secplugs-php-kit)

## Installation
Download from [secplugs.class.php](https://docs.secplugs.com/php-kit/scripts/secplugs.class.php).
Or simply run the commands below in your working directory.
```bash
curl https://docs.secplugs.com/php-kit/scripts/secplugs.class.php -o ./secplugs.class.php
```
You'll now have all the PHP class in the directory ready to use.

## Usage
Usage pattern is to instanciate a client and then use its methods to scan items

### Scan A File
Usage is simple, past the below into a php file in the same directory that you downloaded secplugs.class.php to.
The code will write eicar.com file to a temp location and then scan it.
```php
require_once dirname(__FILE__).'/secplugs.class.php';

$file_scanner = new Secplugs();

// Test eicar
$data = base64_decode('WDVPIVAlQEFQWzRcUFpYNTQoUF4pN0NDKTd9JEVJQ0FSLVNUQU5EQVJELUFOVElWSVJVUy1URVNULUZJTEUhJEgrSCo=');
$eicar_file = '/tmp/eicar.com';
file_put_contents($eicar_file, $data);
$res = $file_scanner->isClean($eicar_file);
unlink($eicar_file);
if ($res) {
    exit(1);
}

// Test clean file
$clean_file = $argv[0];
$res = $file_scanner->isClean($clean_file);
if (!$res) {
    exit(1);
}
```

### Use Your Own API Key
To use additional features and the privacy of your own account, after registering with {brand-name}, sign in with your username and [create an API key](docs?doc=docs/HowTo/CreateKey) 

After creating a key, the only change to the code sample above would be

```php
$file_scanner = new Secplugs("my-api-key");
```

Everything else remains the same.

## Contact
Having trouble? [Contact {brand-name} ](https://{brand-root-domain}/contacts)

