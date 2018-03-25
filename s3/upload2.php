<?php


require 'app/start.php';
use Aws\Exception\AwsException;
echo "hi";

if(isset($_FILES['fileToUpload'])) {
	echo "<p>UPLOADING....</p>";
    
    $file = $_FILES['fileToUpload'];
	$uploadOk = 1;
    	
    //File details
    
    $name = $file['name'];
	echo "<p>" . $name . "</p>";
    $temp_name = $file['tmp_name'];
    
    $extension = explode('.', $name);
	$extension = strtolower(end($extension));
	// var_dump($extension);
	if ($extension == "csv"){
		echo "<p>this file is csv</p>";
		require 'csvProcess.php';
		
	}
	
	// Temp details
	$key = md5(uniqid());
	$temp_file_name = "{$key}.{$extension}";
	$temp_file_path = "files/{$temp_file_name}";
	// var_dump($temp_file_path);
	
	if (file_exists($temp_file_path)) {
		echo "<p>Sorry, file already exists.</p>";
		$uploadOk = 0;
}
   //Move the file
   if ($uploadOk == 0) {
		echo "<p>Sorry, your file was not uploaded.</p>";
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($temp_name, $temp_file_path)) {
			echo "<p>The file ". basename( $name). " has been uploaded.</p>";
		} else {
			echo "<p>Sorry, there was an error uploading your file.</p>";
		}
	}




	try{
		$result = $s3Client->putObject([
			'Bucket'     => $config['s3']['bucket'],
			'Key'        => "uploads/{$name}",
			'SourceFile' => $temp_file_path 
			
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
        <form action="upload2.php" method="post" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" id="fileToUpload"/>
            <input type="submit" value="Upload"/>
        </form>
    </body>
    
</html>
