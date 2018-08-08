$(document).ready(function(){
	$.ajax({
		url: "getJSON",
		method: "GET",
		success: function(data) {
			console.log(data);
			data = JSON.parse(data);

			var player = [];
			var score = [];

			for(var i in data) {
				player.push(data[i].CO);
				score.push(data[i].Timestamp);
			}

			var chartdata = {
				labels: player,
				datasets : [
					{
						label: 'Player Score',
						backgroundColor: 'rgba(200, 200, 200, 0.75)',
						borderColor: 'rgba(200, 200, 200, 0.75)',
						hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
						hoverBorderColor: 'rgba(200, 200, 200, 1)',
						data: score
					}
				]
			};

			var ctx = $("#mycanvas");

			var barGraph = new Chart(ctx, {
				type: 'bar',
				data: chartdata
			});
		},
		error: function(data) {
			console.log(data);
		}
	});
});
