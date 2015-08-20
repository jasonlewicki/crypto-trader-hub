<?php 
// Detect if request was sent from JQuery or not
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
		
	include dirname(__FILE__).'/Configs/config.php';
	include CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.'autoload.php';
	\CryptoTraderHub\Core\Database::initialize(DATABASE_INI);
	$timestamp = $_GET['timestamp'];
	$result  = \CryptoTraderHub\Core\Database::getArray("SELECT timestamp, price, volume, type FROM ".\CryptoTraderHub\Core\Database::getDB().".bitstamp_order_book WHERE `timestamp` = (SELECT `timestamp` FROM ".\CryptoTraderHub\Core\Database::getDB().".bitstamp_order_book WHERE `timestamp` > '{$timestamp}' LIMIT 1) AND volume < 1000 AND price < 300 and price > 200");
	$response = Array('timestamp' => $result[0]['timestamp'], 'data_set' => $result);
	echo json_encode($response);
	exit();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		
		<title>Bitstamp Order Book Visualizer</title>
		
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<!--[if gte IE 9]>
		  <style type="text/css">
		    .gradient {
		       filter: none;
		    }
		  </style>
		<![endif]-->		
		
		<style></style>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>	
		<script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/jquery.canvasjs.min.js"></script>	
		<script type="text/javascript">
			
			var timer;
			var dps = [];   //dataPoints. 
			var chart;
			var xVal = dps.length + 1;
	      	var yVal = 15;	
	      	var updateInterval = 250;
	      	var timestamp = "1977-01-01 00:00:00";
			
			$(function() {
				
		    	chart = new CanvasJS.Chart("chartContainer",{
		      		title :{
		      			text: "Order Book Data"
		      		},
		      		axisX: {						
		      			title: "Price"
		      		},
		      		axisY: {						
		      			title: "Volume"
		      		},
		      		data: [{
		      			type: "scatter",
		      			dataPoints : dps
		      		}]
		      	});  	
		      	chart.render();		      	
			
				$('#start').click(function() {
					timer = setInterval(function(){updateChart()}, updateInterval);
				});
				
				$('#stop').click(function() {
					clearInterval(timer);
				});
				
			});		
			
			function updateChart() {		      		
	      		$.ajax({
				  	url: "/",
				  	context: document.body,
				  	dataType: 'json',
				  	data: {'timestamp':timestamp}
				}).done(function(data) {
					timestamp = data.timestamp;
					dps.length = 0;
					
					// Update timestamp
					$('#timestamp').html(timestamp);
					
					// Add Asks Cumulative Volume
					var ask_volume_total = 0;
					$.each(data.data_set, function( index, value ) {
						if(value.type == "ask"){
							ask_volume_total += parseFloat(value.volume);
						  	dps.push({x:parseFloat(value.price),y:parseFloat(ask_volume_total)});
						 }
					});		
					
					// Add Bids	Cumulative Volume					
					var bid_volume_total = 0;
					var data_reversed = data.data_set.slice().reverse();
					$.each(data_reversed, function( index, value ) {
						if(value.type == "bid"){
							bid_volume_total += parseFloat(value.volume);
						  	dps.push({x:parseFloat(value.price),y:parseFloat(bid_volume_total)});
						 }
					});
					
					// Plain data points (alternative)
					/*$.each(data.data_set, function( index, value ) {
					  	dps.push({x:parseFloat(value.price),y:parseFloat(value.volume)});
					});*/						
								
		      		chart.render();
				});			      				
			}		
			
		</script>

	</head>
	<body>			
		<button id="start">Start</button>
		<button id="stop">Stop</button>
		<span id="timestamp"></span>
		<div id="chartContainer" style="height: 850px; width:100%;"></div>		
	</body>
</html>
