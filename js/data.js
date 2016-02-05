function setCountry(country, overallActions) {
    document.querySelector('.country_tag').innerHTML = country + " - Protests 1980-1995: " + overallActions;
}

function getWordData() {

        var searchString = $("#word").val();
        console.log("This function was called!");
        console.log(searchString);

        $.ajax({
                method: "get",
                url: "advq.php",
                data: {words: searchString},
                success: function(result, textStatus, jqXHR) {
                console.log(result);

                var actions = JSON.parse(result);
                $("#example").DataTable({data: actions});
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
    }});
}


function getChartData(countryName) {

	$.ajax({
		method: "get",
		url: "index.php", 
		data: {id: countryName},
		success: function(result, textStatus, jqXHR) {
            	console.log(result);

            	var actions = JSON.parse(result);
		var eventsYear = actions["eventsYear"];
		var eventsPerYear = actions["eventsPerYear"];
	    	var annualActions = actions["annualActions"];
	    	var overallActions = actions["overallActions"];
	    	var actionTypes = actions["actionTypes"];
		var actionDescription = actions["actionDescription"];
		var barChartData = [annualActions[0],annualActions[1],annualActions[2],annualActions[3],
			    annualActions[4],annualActions[5],annualActions[6],annualActions[7],
			    annualActions[8],annualActions[9],annualActions[10],annualActions[11],
		            annualActions[12],annualActions[13],annualActions[14],annualActions[15]]
	    	setCountry(countryName, overallActions);
	    	populateCharts(barChartData, actionTypes, actionDescription);
	},
	error: function(jqXHR, textStatus, errorThrown) {
	    console.log(textStatus);
    }});
}

// Update function of all charts to make sure that only the most recent data is being displayed
function populateCharts(barChartData, actionTypes, actionDescription) {

	// Bar charts asynchronous update
	for (i = 0; i < 16; i++) {

      		if (typeof barChartData[i] !== undefined && barChartData[i] != null) {
       			window.myBar.datasets[0].bars[i].value = barChartData[i];
       			window.myWideBar.datasets[0].points[i].value = barChartData[i];
       		} 
		else {
       			window.myBar.datasets[0].bars[i].value = 0;
       			window.myBar.datasets[0].points[i].value = 0;
       		}
	}
    
	window.myBar.update();
	window.myWideBar.update();

	// Pie charts asynchronous update
	for (i=0; i<5; i++) {
		window.myPie.segments[i].value = actionTypes[i];
		window.myPie.segments[i].label = actionDescription[i];
		//window.myPieAlt.segments[i].value = actionTypes[i];
                //window.myPieAlt.segments[i].label = actionDescription[i];

	}

	window.myPie.update();
    	window.myPieAlt.update();
}

function makeCharts() {
    var barChartData = {
	labels : ["1980","1981","1982","1983","1984","1985","1986","1987",
		  "1988","1989","1990","1991","1992","1993","1994","1995"],
	datasets : [
	    {
		fillColor : "rgba(220,220,220,0.5)",
		strokeColor : "rgba(220,220,220,0.8)",
		highlightFill: "rgba(220,220,220,0.75)",
		highlightStroke: "rgba(220,220,220,1)",
		data : ["0","0","0","0",
		    "0","0","0","0",
		    "0","0","0","0",
		    "0","0","0","0"]
	    }
	
	]
    }
 
    var pieData = [
				{
					value: 300,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "Red"
				},
				{
					value: 50,
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: "Green"
				},
				{
					value: 100,
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "Yellow"
				},
				{
					value: 40,
					color: "#949FB1",
					highlight: "#A8B3C5",
					label: "Grey"
				},
				{
					value: 120,
					color: "#4D5360",
					highlight: "#616774",
					label: "Dark Grey"
				}

			];

 var pieDataEurope = [
                                {
                                        value: 19355,
                                        color:"#F7464A",
                                        highlight: "#FF5A5E",
                                        label: "occupation"
                                },
                                {
                                        value: 9687,
                                        color: "#46BFBD",
                                        highlight: "#5AD3D1",
                                        label: "demonstration"
                                },
                                {
                                        value: 6412,
                                        color: "#FDB45C",
                                        highlight: "#FFC870",
                                        label: "hunger strike"
                                },
                                {
                                        value: 4542,
                                        color: "#949FB1",
                                        highlight: "#A8B3C5",
                                        label: "obstrucition"
                                },
                                {
                                        value: 3048,
                                        color: "#4D5360",
                                        highlight: "#616774",
                                        label: "hostage-nd"
                                }

                        ];


    var radarData = {
		labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
		datasets: [
			{
				label: "Country Specific",
				fillColor: "rgba(220,220,220,0.2)",
				strokeColor: "rgba(220,220,220,1)",
				pointColor: "rgba(220,220,220,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(220,220,220,1)",
				data: [65,59,90,81,56,55,40,12,67,25,79,23]
			},
			{
				label: "Across Europe",
				fillColor: "rgba(151,187,205,0.2)",
				strokeColor: "rgba(151,187,205,1)",
				pointColor: "rgba(151,187,205,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(151,187,205,1)",
				data: [28,48,40,19,96,27,100]
			}
		]
	};


    var ctx = document.getElementById("canvas").getContext("2d");
    window.myBar = new Chart(ctx).Bar(barChartData, {
	responsive : true
    });
    var ctx = document.getElementById("canvas_alt").getContext("2d");
    window.myWideBar = new Chart(ctx).Line(barChartData, {
	responsive : true
    });

    var ctx = document.getElementById("canvas_pie").getContext("2d");
    window.myPie = new Chart(ctx).Doughnut(pieData, {responsive:true});

    var ctx = document.getElementById("canvas_pie_alt").getContext("2d");
    window.myPieAlt = new Chart(ctx).Doughnut(pieDataEurope, {responsive:true});

//    var ctx = document.getElementById("canvas_radar").getContext("2d");
 //   window.myRadar = new Chart(ctx).Radar(radarData, {responsive:true});

   // var ctx = document.getElementById("canvas_radar_alt").getContext("2d");
   // window.myRadarAlt = new Chart(ctx).Radar(radarData, {responsive:true});


}
