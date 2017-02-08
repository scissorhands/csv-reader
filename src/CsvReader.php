<?php
namespace Scissorhands\Tools;
/**
 * CSV Reader Library class
 *
 * @author Edward Scissorhands 
 **/
class CsvReader {
	var $fields;
    var $separator = ',';
    var $enclosure = '"';
    var $max_row_size = 4096;

	public function formatHeader($str)
	{
		return strtolower(str_replace(" ", "_", $str));
	}

    public function set_separator( $separator )
    {
        $this->separator = $separator;
    }

    /**
     * Parses a given CSV file into a stdClass Objects Array
     *
     * @return StdClass Object Array
     * @param String $filePath: Absolute path of the Csv File
     * @param Boolean $namedColumns
     * @param Int $linesToSkip
     **/
	function parse_file( $filePath, $namedColumns=true, $linesToSkip=0 ) {
        $content = false;
        $file = fopen($filePath, 'r');
        for ($i=0; $i < $linesToSkip; $i++) { 
            fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure);
        }
        if($namedColumns) {
            $array_fields = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure);
            $this->fields = array_map([$this, "formatHeader"], $array_fields );
        }
        while( ($row = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure)) != false ) {            
            if( $row[0] != null ) { 
                if( !$content ) {
                    $content = [];
                }
                if( $namedColumns ) {
                    $items = [];
                    foreach( $this->fields as $id => $field ) {
                        if( isset($row[$id]) ) {
                            $items[$field] = $row[$id];    
                        }
                    }
                    $content[] = (Object)$items;
                } else {
                    $content[] = $row;
                }
            }
        }
        fclose($file);
        return $content;
    }
}