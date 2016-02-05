<?php

        $host = "hex.cl.uzh.ch";
        $port = 65432;
        $dbname = "pcl3fancystuff";
        $user = "pcl3cinque";
        $password = "ooN5Iedi";

        $country = $_GET["id"];
 	$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

	// Check if a connection to the database can be established
        if(!$db){
        	echo "alert(Error: Unable to open database!)";
        }

	$actionsAnnualTotal = array();
	$actionsOverallTotal = 0;
        $actionsCounter = 0;

	// Check if a country is set or the default page is being accessed
	if ($country) {		

		// echo "alert(Country mode)";
		if ($country == "UK" || $country == "Northern Ireland") {
			$result = pg_query ($db, "SELECT COUNT(*) as count FROM fancystuff.protests 
						  WHERE country like 'UK%' GROUP BY year ORDER BY year ASC");
			$total = pg_query ($db, "SELECT COUNT(*) as count FROM fancystuff.protests WHERE country LIKE 'UK%'");
                         $stats = pg_query ($db, "SELECT COUNT(action) AS count, action FROM fancystuff.protests
                                               WHERE country LIKE 'UK%' GROUP BY action ORDER BY count DESC");
		} 
		else if ($country == "Germany") {
			$result = pg_query ($db, "SELECT COUNT(*) AS count FROM fancystuff.protests 
						  WHERE country like '%German%' GROUP BY year ORDER BY year ASC");
			$total = pg_query ($db ,"SELECT COUNT(*) AS count FROM fancystuff.protests WHERE country LIKE '%German%'");
                        $stats = pg_query ($db, "SELECT COUNT(action) AS count, action FROM fancystuff.protests
                                               WHERE country LIKE '%German%' GROUP BY action ORDER BY count DESC");
		}
		else if ($country == "Czech" || $country == "Czechoslovakia") {
  			$result = pg_query ($db, "SELECT COUNT(*) AS count FROM fancystuff.protests 
						  WHERE country='Czech Republic' GROUP BY year ORDER BY year ASC");
                        $total = pg_query ($db ,"SELECT COUNT(*) AS count FROM fancystuff.protests WHERE country LIKE 'Czech%'");
			 $stats = pg_query ($db, "SELECT COUNT(action) AS count, action FROM fancystuff.protests
                                               WHERE country LIKE 'Czech%' GROUP BY action ORDER BY count DESC");
		}
		else if ($country == "europe") {
			$result = pg_query ($db, "SELECT COUNT(*) AS count, year FROM fancystuff.protests 
						  GROUP BY year ORDER BY year ASC OFFSET 16");
			$total = pg_query  ($db, "SELECT COUNT(*) AS count FROM fancystuff.protests");
			$stats = pg_query  ($db, "SELECT COUNT(action) AS count, action FROM fancystuff.protests
						  GROUP BY action ORDER BY count DESC");
			$events = pg_query ($db, "SELECT COUNT(*) AS count FROM fancystuff.protests GROUP BY year ORDER BY count DESC");
		} 
		else {
			$result = pg_query($db, "SELECT COUNT(*) AS count, year FROM fancystuff.protests 
						 WHERE country='$country' GROUP BY year ORDER BY year ASC");
			$total = pg_query($db,  "SELECT COUNT(*) AS count FROM fancystuff.protests WHERE country='$country'");
                        $stats = pg_query ($db, "SELECT COUNT(action) AS count, action FROM fancystuff.protests
                                                 WHERE country='$country' GROUP BY action ORDER BY count DESC");


}

		if ((!$result || !$total) && !$stats) {
                	echo "alert(Error!)";
        	}
		else {
			$lastYear = 0;
			$row = pg_fetch_row($total);
			$actionTyp = pg_fetch_row($stats);
			
			$eventsPerYear = array();
			$eventsYear = array();			
			$actionTypesList = array();
			$actionDescription = array();
			$actionsOverallTotal = array();

                	while ($singleEvent = pg_fetch_row($result)) {

				if ($actionsCounter == 0) {
					
					$lastYear = $singleEvent[1];
					
					if ($lastYear == 80 || $lastYear == 1980) {
						$actionsAnnualTotal[$actionsCounter] = $singleEvent[0];
					}
					else {
						$actionsAnnualTotal[$actionsCounter] = 0;
					}
				}					

				if ($actionsCounter != 0) {
					
					//if ($lastYear + 1 == $singleEvent[1]) {
						$actionsAnnualTotal[$actionsCounter] = $singleEvent[0];
					//}
					//else {
					//	$actionsAnnualTotal[$actionsCounter] = 0;
					//}

					$lastYear = $singleEvent[1];
				}
				
				$actionsCounter++;
			}
				
			$actionsCounter = 0;			

			while ($topAction = pg_fetch_row($stats)) {
				$actionTypesList[$actionsCounter] = $topAction[0];
				$actionDescription[$actionsCounter] = $topAction[1];
				$actionsCounter++;
			}

			$actionsCounter = 0;

			while ($nextEvent = pg_fetch_row($events)) {
				$eventsPerYear[$actionsCounter] = $nextEvent[0];
				$eventsYear[$actionsCounter] = $nextEvent[1];
			}
			
			$actionsCounter = 0;
			$currentYear = 0;
        	}
	}
	else {
		// echo "alert(Default mode)";
	}

	$actionsCounter = 0;
	
	$return = array("annualActions" => $actionsAnnualTotal, "overallActions" => $actionsOverallTotal,
			"actionTypes" => $actionTypesList, "actionDescription" => $actionDescription,
			"eventsPerYear" => $eventsPerYear, "eventYear" => $eventYear);
	echo json_encode($return);

?>
