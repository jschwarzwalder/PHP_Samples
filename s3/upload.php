<?php


require 'app/start.php';
use Aws\Exception\AwsException;

if(isset($_FILES['file'])) {
    
    $file = $_FILES['file'];
    
    //File details
    
    $name = $file['name'];
    $temp_name = $file['tmp_name'];
    
    $extension = explode('.', $name);
	$extension = strtolower(end($extension));
	// var_dump($extension);
	
	// Temp details
	$key = md5(uniqid());
	$temp_file_name = "{$key}.{$extension}";
	$temp_file_path = "files/{$temp_file_name}";
	// var_dump($temp_file_path);
	
	
   //Move the file
   move_uploaded_file($temp_name, $temp_file_path);



try{
    $result = $s3Client->putObject([
        'Bucket'     => $config['s3']['bucket'],
        'Key'        => "uploads/{$name}",
        
    ]);
} catch (S3Exception $e) {
    echo $e->getMessage() . "\n";
}
    
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
