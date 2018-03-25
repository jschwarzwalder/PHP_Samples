<?php

$PHP1 = array('total_customers' => 0, 'total_requests' => 0 ); //PHP SDK V1
$PHP2 = array('total_customers' => 0, 'total_requests' => 0 ); //PHP SDK V2
$PHP3 = array('total_customers' => 0, 'total_requests' => 0 ); //PHP SDK V3
$PHPSDK = array('total_customers' => 0, 'total_requests' => 0 ); //PHP SDK Total
$SDK = array('total_customers' => 0, 'total_requests' => 0 ); //All SDKs

function cal_percents(&$sdk) {
	$total_customers = floatval($sdk['total_customers']);
	$total_requests = $sdk['total_requests'];
	foreach ($sdk as &$service){
		if (is_array($service)){
			$service['customer_percent'] = floatval($service['customers'] / $total_customers) * 100;
			$service['requests_percent'] = ($service['requests'] / $total_requests )* 100;
		}
	}
	unset($service);
	
}

function cal_total_percents(&$sdk, $SDK) {
	$total_customers = floatval($SDK['total_customers']);
	$total_requests = $SDK['total_requests'];
	foreach ($sdk as &$sdk_service){
		if (is_array($sdk_service)){
			$sdk_service['all_customer_percent'] = floatval($sdk_service['customers'] / $total_customers) * 100;
			$sdk_service['all_requests_percent'] = ($sdk_service['requests'] / $total_requests )* 100;
		}
	}
	unset($sdk_service);
	
}


function add_to_array(&$sdk, $data){
		$service['name'] = $data[1];	
		$service['customers'] = intval($data[2]);
		$service['requests'] = intval($data[3]);
		array_push($sdk, $service);
		$sdk['total_customers'] += $service['customers'];
		$sdk['total_requests'] += $service['requests'];
		
}

function compare_customers($a, $b) {
	if (is_array($a) && is_array($b)){
		$a_value = $a['customers'];
		$b_value = $b['customers'];
		return $b_value - $a_value;
	} else {
		return 0;
	}
}

function compare_requests($a, $b) {
	if (is_array($a) && is_array($b)){
		$a_value = $a['requests'];
		$b_value = $b['requests'];
		return $b_value - $a_value;
	} else {
		return 0;
	}
}


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
//echo fread($myfile,filesize($temp_name));

// $data = fgetcsv($myfile, 0, ",");
//var_dump( $data);
//array(5) { 
//	[0]=> string(4) "tool" 
//	[1]=> string(7) "service" 
//	[2]=> string(9) "customers" 
//	[3]=> string(8) "requests" 
//	[4]=> string(0) "" }

while (($data = fgetcsv($myfile, 0, ",")) !== FALSE) { 
	$tool = $data[0];
	//echo "<p>" . $tool . "</p>";
	if ($tool == "PHP SDK V1"){
		add_to_array($PHP1, $data);
	} else if ($tool == "PHP SDK V2") {
		add_to_array($PHP2, $data);
	} else if ($tool == "PHP SDK V3") {
		add_to_array($PHP3, $data);
	} else if ($tool == "PHP SDK Total") {
		add_to_array($PHPSDK, $data);		
	} else if ($tool == "All SDKs") {
		add_to_array($SDK, $data);
		
	}
}

fclose($myfile);



cal_percents($SDK);
cal_percents($PHP1);
cal_percents($PHP2);
cal_percents($PHP3);
cal_percents($PHPSDK);
cal_total_percents($PHP1, $SDK);
cal_total_percents($PHP2, $SDK);
cal_total_percents($PHP3, $SDK);
cal_total_percents($PHPSDK, $SDK);


echo "<p>Total Customers = " . $PHPSDK['total_customers'] . "</p>";
echo "<p>Total Requests = ". $PHPSDK['total_requests'] .  "</p>";
echo "<table>";
echo "<tr>";
echo "<th>Service</th>";
echo "<th>Customers</th>";
echo "<th>Requests</th>";
echo "<th> Customers %</th>";
echo "<th>Requests %</th>";
echo "</tr>";
usort($PHPSDK, "compare_customers");
for ($i = 0; $i < 15; $i++){
	echo "<tr>";
	echo "<td>" . $PHPSDK[$i]['name'] . "</td>";
	echo "<td>" . $PHPSDK[$i]['customers'] . "</td>";
	echo "<td>" . $PHPSDK[$i]['requests'] . "</td>";
	echo "<td>" . $PHPSDK[$i]['customer_percent'] . "</td>";
	echo "<td>" . $PHPSDK[$i]['requests_percent'] . "</td>";
	echo "</tr>";
}
echo "</table>";

?>