<?php

	$host = "hex.cl.uzh.ch";
	$port = 65432;
	$dbname = "pcl3fancystuff";
	$user = "pcl3cinque";
	$password = "ooN5Iedi";

	$country = $_GET["id"];

   	$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
   	
	if(!$db){
    	echo "<br/>Error: Unable to open database!";
   	} 
	else {
      	echo "<br/>Opened database successfully!";
   	}

	$json = array();
	$jsonDescription = array();

	$result = pg_query($db, "SELECT COUNT(*) as count FROM fancystuff.protests WHERE country='$country'");
	if (!$result){
		echo "Didn't work";
	}
	else{
		$row = pg_fetch_row($result);
		echo $row[0];
		echo $country."<br/>";
	}

	$events = pg_query($db, "SELECT COUNT(action) AS count, action FROM fancystuff.protests GROUP BY action ORDER BY count DESC");

	$counter = 0;
	while ($singleEvent = pg_fetch_row($events)) {
		$json[$counter] = $singleEvent[0];
		$jsonDescription[$counter] = $singleEvent[1];
		$counter++;
		echo "Total: $singleEvent[0] Event: $singleEvent[1] <br/>";
	}

	$ar = array('apple', 'orange', 1, false, null, true, 3 + 5);
	$ar2 = array($events);

?>

<!doctype html>

<html>
	<head>
		<title>Bar Chart</title>
		<script src="charts/Chart.js"></script>
	</head>
	<body>
	<table>
		<tr>
		<td>
			<canvas id="canvas" height="450" width="600"></canvas>
		</td>
		<td>	
			<canvas id="chart-area" width="450" height="450"/>
		</td>
		</tr>
	<table>
	<script type="text/javascript">
		var ar = <?php echo json_encode($json) ?>;
		var eventDescription = <?php echo json_encode($jsonDescription) ?>;
		//alert( ar[1] ); // false
	</script>

	<script>
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
	
	var php_var = <?php echo $row[0]; ?>;
	
	document.write('<div>Print this after the script tag</div>');

	var barChartData = {
		labels : [eventDescription[0], eventDescription[1], eventDescription[2], eventDescription[3], eventDescription[4]],
		datasets : [
			{
				fillColor : "rgba(220,220,220,0.5)",
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
				data : [ar[0], ar[1], ar[2], ar[3], ar[4]]
			}
		]

	}

	var pieData = [
				{
					value: ar[0],
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: eventDescription[0]
				},
				{
					value: ar[1],
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: eventDescription[1]
				},
				{
					value: ar[2],
					color: "#FDB45C",
					highlight: "#FFC870",
					label: eventDescription[2]
				},
				{
					value: ar[3],
					color: "#949FB1",
					highlight: "#A8B3C5",
					label: eventDescription[3]
				},
				{
					value: ar[4],
					color: "#4D5360",
					highlight: "#616774",
					label: eventDescription[4]
				}

			];

	
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});

		var ctx = document.getElementById("chart-area").getContext("2d");
		window.myPie = new Chart(ctx).Pie(pieData);
	}

	</script>
	
	</body>
</html>

