File-Class
==========
A easy way of using files in php

## A small preview
```php
<?php
  require ('File.php');
  
  $testFile = new File('test.php'); // Creates or opens a file.
  $testFile->Content = 'Hello World!'; // Sets Hello World! to the content.
  // When script ends the file will be writen
  
```

## All function preview
```php
<?php
  require('File.php');
  
  $testFile = new File('test.php', false); // Creates or opens a file. (false = auto save off)
  $fileContent = $testFile->Content; // Gets content of file and sets variable.
  $testFile->Content = "Bye World!"; // Set new content of the file.
  if ($testFile->Content !== $fileContent) { // Check if the content and old content are the same (if not run this)
    unset($testFile->Content); // Clears file content;
    $testFile->resetContent() // Resets filecontent to old content;
  }
  $testFile->save(); // Save File
  
  File::move("test.php", "../newtest.php"); //Move file to another location
  File::rename("../newtest.php", "test.php"); //Rename file
  File::copy("../test.php", "test.php"); //Copy file
  if (File::exists("../test.php")) { // Checks if file exists
    File::delete("../test.php"); // If exists delete file
  }
```
