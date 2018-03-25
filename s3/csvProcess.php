<?php
echo "<p>making bucket</p>";

$new_bucket = "php-stats";

$buckets = $s3Client->listBuckets();
$the_buckets = array();
foreach ($buckets['Buckets'] as $bucket){
	//echo "<p>". $bucket['Name']."</p>\n";
	array_push($the_buckets, $bucket['Name']);
}
if (!in_array($new_bucket, $the_buckets)){
	try {
		$result = $s3Client->createBucket([
			'Bucket' => $new_bucket,
		]);
	}catch (AwsException $e) {
		// output error message if fails
		echo $e->getMessage();
		echo "\n";
	}
} 

$config['s3']['bucket'] = $new_bucket;

echo "<p>Reading file</p>";

$myfile = fopen($temp_name, "r") or die("<p>Unable to open file!</p>");
echo fread($myfile,filesize($temp_name));
fclose($myfile);


?>