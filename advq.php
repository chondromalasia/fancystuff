<?php

        $host = "hex.cl.uzh.ch";
        $port = 65432;
        $dbname = "pcl3fancystuff";
        $user = "pcl3cinque";
        $password = "ooN5Iedi";

        $search_string = $_GET["words"];
	//$search_string = "eat";
        $db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

        $words = explode(" ", $search_string);

	// Check if a connection to the database can be established
        if(!$db){
        	echo "alert(Error: Unable to open database!)";
        } else {

            $countries = array();
            $greatest_number = 0;

            // Check if a country is set or the default page is being accessed
            $query =  'SELECT distinct(x.event, country, count) FROM
			(SELECT count(*) as count, event as event
                        FROM fancystuff.protests2 WHERE 
                        event_ts @@ to_tsquery($1) GROUP BY event) as x
			JOIN fancystuff.protests2 ON x.event = fancystuff.protests2.event';
            
            $result = pg_query_params($db, $query, array(implode(" & ", $words)));
	    $i = 0;

            while($row = pg_fetch_row($result)) {
                
                $results = explode("\",", $row[0]);
		$country_and_count = explode(",", $results[1]);
		if (count($country_and_count) == 2) {
			$results[1] = $country_and_count[0];
			$results[2] = $country_and_count[1];
		} else {
			$results[2] = "";
			$results[1] = $country_and_count[0];
		}
                foreach($results as &$val) {
			$val = trim($val, " \\\"()");
		}
                $return[$i] = $results;
                $i += 1;
            }
            echo json_encode($return);		
        }
?>
