<?php

require 'app/start.php';

if(isset($_FILES['file'])) {
    
    $file = $_FILES['file'];
    
    //File details
    
    $name = $file['name'];
    $temp_name = $file['tmp_name'];
    
    $extension = exlopde('.', $name);
    

    
}

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Upload</title>
    </head>
    <body>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file"/>
            <input type="submit" value="Upload"/>
        </form>
    </body>
    
</html>
