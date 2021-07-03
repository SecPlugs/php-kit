#### What is the PHP Kit?

A set of samples and tools written in written in PHP ready to use. 
Currently the plugin supports file upload and scanning from the command line.

#### What are the features?

- __File Scanning__ - Easy functions to allow uploading o files for scanning
- __Secplugs Portal__ - With a registered API key you can access all the core Secplugs features via the portal.

#### How does it work?

The library supports all the standard Secplugs functionality allowing access to file analysis functionality and makes it easier than integrating directly with the REST APIs.

#### How do I get started?

To get started download the sample scripts and run them under PHP 7.x or later. They will work out of the box ready for you to tailor.

To use additional features and the privacy of your own account, after registering in Secplugs.com, login with your username and create an API key to use with the scripts. 
Replace the key in the samples or create new scripts using these as an example.
Use can then use the Secplugs console to view activity, run reports and do deeper retrospective threat analysis.

#### Working with composer

If you have a php project that uses composer to manage dependencies, it is very easy to add and integrate secplugs file scanning to your project. Just run

```composer require secplugs/filescan```

from the root of your project and add the following lines to the top of the php file that handles file uploads.


    require_once($PROJECT_ROOT.'/vendor/autoload.php');
    use Secplugs/FileScan;
    
    $filescan = new FileScan($my_secplugs_api_key);
    ...
    ...
    ...
    if ($filescan->isClean($uploaded_file)) {
        // File upload handling
    } else {
        // Return error.
    }
    
Adding these 5-6 lines of code would automatically analyse all uploaded files for malware before you store/process those files.


