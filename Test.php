<?php 
require '/src/CsvReader.php';
use Scissorhands\Tools\CsvReader as Reader;

$reader = new Reader();

$data = $reader->parse_file('assets/example.csv');
echo "<pre>";
	print_r( $data );
echo "</pre>"
?>