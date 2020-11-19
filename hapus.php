<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>upload file</title>
</head>
<body>
<?php
    $dir = "upload/";
    if (is_dir($dir)) { 
        $objects = scandir($dir);
        foreach ($objects as $object) { 
          if ($object != "." && $object != "..") { 
            if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
              rrmdir($dir. DIRECTORY_SEPARATOR .$object);
            else
              unlink($dir. DIRECTORY_SEPARATOR .$object); 
          } 
        }
        rmdir($dir); 
      }
      
      mkdir("upload");
      header('location: collection.php');
?>


</body>
</html>




