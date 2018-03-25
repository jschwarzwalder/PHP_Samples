<?php
/**
 * Copyright 2010-2018 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * This file is licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License. A copy of
 * the License is located at
 *
 * http://aws.amazon.com/apache2.0/
 *
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 *
 * ABOUT THIS PHP SAMPLE: This sample is part of the SDK for PHP Developer Guide topic at
 * https://docs.aws.amazon.com/aws-sdk-php/v3/guide/examples/s3-examples-creating-buckets.html
 *
 */

require '../../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

/**
 * List your Amazon S3 buckets.
 *
 * This code expects that you have AWS credentials set up per:
 * http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/credentials.html
 */

 $bucket_to_remove = 'jami-test-s3';
 
//Create a S3Client
$s3Client = new S3Client([
	'profile' => 'awssdkphp',
    'region' => 'us-west-2',
    'version' => '2006-03-01'
]);

echo "<h2>List out all buckets</h2>";
//Listing all S3 Bucket
$buckets = $s3Client->listBuckets();
foreach ($buckets['Buckets'] as $bucket){
	echo "<p>". $bucket['Name']."</p>\n";
}
echo "<h3>Print out Files in bucket</h3>";

$objects = $s3Client ->listObjects([
	'Bucket' => $bucket_to_remove,
	]);

// Listing all objects in bucket
$test_objects = array();
foreach ($objects['Contents'] as $file){
	//echo "<p>". $file['Key']."</p>\n";
	array_push($test_objects, $file['Key']);
}

//Removing objects in test bucket
foreach($test_objects as $file){
	$result = $s3Client->deleteObject([
		'Bucket' => $bucket_to_remove,
		'Key' => $file,
	]);
}

echo "<h3>Delete test bucket</h3>";
$objects = $s3Client ->deleteBucket([
	'Bucket' => $bucket_to_remove,
	]);


echo "<h2>List out all buckets</h2>";	
$buckets = $s3Client->listBuckets();
foreach ($buckets['Buckets'] as $bucket){
	echo "<p>". $bucket['Name']."</p>\n";
}