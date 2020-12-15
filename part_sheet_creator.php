<?php 

$file1 = fopen("vechile_details.csv","r");
$file2 = fopen("vechile_details_part.csv","w");

$header = array("Make","Model","Engine Type","SKU");
fputcsv($file2, $header);
$c1 = 0;

$manufacturerCollection = [];

while (($row = fgetcsv($file1)) != false) {
	$c1++;
	if ($c1 == 1){
		continue;
	} 
	
	$manu = $row[3];
	$model = $row[4];
	$yearfrom = $row[8];
	$date_from = $row[9];
	$yearto = $row[10];
	$date_to = $row[11];

	
	$data = array($row[3],$row[4].getSimilarCsvRows($manu,$model,$date_from,$yearfrom,$date_to,$yearto),$row[2]."( ".$row[7]." KW"." / ".$row[6]."HP ) ".$row[5]." ( ".((!empty($yearfrom)) ? substr_replace( $yearfrom, "/", 4, 0 ): '...')." - ".((!empty($yearto)) ? substr_replace( $yearto, "/", 4, 0 ): '...').")" ,$row[1]);

	fputcsv($file2, $data);

	
}

function getSimilarCsvRows($manu,$model,$yearfrom,$year_from,$yearto,$year_to){
	$file = fopen("vechile_details.csv","r");

	$c = 0;
	$mindate = $yearfrom;
	$minFrom = $year_from;
	$maxdate = $yearto;
	$mavTo = $year_to;
	while (($row = fgetcsv($file))!=false) {
		$c++;
		if ($c == 1){
			continue;
		} 
		
		if ($row[3] == $manu && $row[4] == $model) {
			//echo $row[0]." ".$row[3]." ".$row[4]."\n";
			if ($mindate >= $row[9]) {
				$mindate = $row[9];
				$minFrom = $row[8];
			}
			if ($maxdate <= $row[11]) {
				$maxdate = $row[11];
				$mavTo = $row[10];
			}
		}
	}
	fclose($file);

	$date_string = " (".((!empty($minFrom)) ? substr_replace( $minFrom, "/", 4, 0 ): '...' )." - ".((!empty($mavTo))?substr_replace( $mavTo, "/", 4, 0 ): '...').")";
	
	return $date_string;

}
	

fclose($file2);
fclose($file1);

echo "all done";
?>

