var timeOutId = 0;
var ajaxFn = function(){
  $.ajax({
    url : "/getJSON",
    type : "GET",
    success : function(data){
      // console.log(data);
      data = JSON.parse(data);

      var timestamp = [];
      var CO  = [];
      var SO2 = [];
      var NO2 = [];
      var O3  = [];
      var PM2_5 = [];

      for(var i in data) {

        timestamp.push(data[i].Timestamp);
        CO.push(data[i].CO);
        SO2.push(data[i].SO2);
        NO2.push(data[i].NO2);
        O3.push(data[i].O3);
        PM2_5.push(data[i].PM2_5);
      }

      var chartdata = {
        labels: timestamp,
        datasets: [
          {
            label: "CO",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(59, 89, 152, 0.75)",
            borderColor: "rgba(59, 89, 152, 1)",
            pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
            pointHoverBorderColor: "rgba(59, 89, 152, 1)",
            data: CO
          },
          {
            label: "SO2",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(211, 72, 54, 0.75)",
            borderColor: "rgba(211, 72, 54, 1)",
            pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
            pointHoverBorderColor: "rgba(211, 72, 54, 1)",
            data: SO2
          },
          {
            label: "NO2",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(59, 89, 152, 0.75)",
            borderColor: "rgba(59, 89, 152, 1)",
            pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
            pointHoverBorderColor: "rgba(59, 89, 152, 1)",
            data: NO2
          },
          {
            label: "O3",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(29, 202, 255, 0.75)",
            borderColor: "rgba(29, 202, 255, 1)",
            pointHoverBackgroundColor: "rgba(29, 202, 255, 1)",
            pointHoverBorderColor: "rgba(29, 202, 255, 1)",
            data: O3
          },
          {
            label: "PM2.5",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(211, 72, 54, 0.75)",
            borderColor: "rgba(211, 72, 54, 1)",
            pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
            pointHoverBorderColor: "rgba(211, 72, 54, 1)",
            data: PM2_5
          }
        ]
      };

      var ctx = $("#mycanvas");

      var LineGraph = new Chart(ctx, {
        type: 'line',
        data: chartdata
      });
    },
    error : function(data) {

    }
  });
}

ajaxFn();
//OR use BELOW line to wait 10 secs before first call
setInterval(ajaxFn, 5000);
