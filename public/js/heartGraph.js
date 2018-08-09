var timeOutId = 0;
var ajaxFn = function(){
  $.ajax({
    url : "/getJSON/heartReal",
    type : "GET",
    success : function(data){
      //console.log(data);
      data = JSON.parse(data);

      var timestamp = [];
      var heartRate  = [];
      var heartInterval = [];

      len = data.length;
      for(var i in data) {
        back = len - i - 1;
        timestamp.push(data[back].hh + 'h ' + data[back].mm + 'm ' + data[back].ss + 's ' );
        heartRate.push(data[back].HeartRate);
        heartInterval.push(data[back].HeartInterval);
      }

      var chartdata = {
        labels: timestamp,
        datasets: [
          {
            label: "Heart Rate",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(59, 89, 152, 0.75)",
            borderColor: "rgba(59, 89, 152, 1)",
            pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
            pointHoverBorderColor: "rgba(59, 89, 152, 1)",
            data: heartRate
          },
          {
            label: "Heart Interval",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(211, 72, 54, 0.75)",
            borderColor: "rgba(211, 72, 54, 1)",
            pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
            pointHoverBorderColor: "rgba(211, 72, 54, 1)",
            data: heartInterval
          }
        ]
      };
      var heartRateData = {
        labels: timestamp,
        datasets: [
          {
            label: "Heart Rate",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(211, 72, 54, 0.75)",
            borderColor: "rgba(211, 72, 54, 1)",
            pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
            pointHoverBorderColor: "rgba(211, 72, 54, 1)",
            data: heartRate
          }
        ]
      };
      var heartIntervaldata = {
        labels: timestamp,
        datasets: [
          {
            label: "Heart Interval",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(59, 89, 152, 0.75)",
            borderColor: "rgba(59, 89, 152, 1)",
            pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
            pointHoverBorderColor: "rgba(59, 89, 152, 1)",
            data: heartInterval
          }
        ]
      };

      var ctx = $("#mycanvas");
      var heartRatectx = $("#heartRateChart");
      var heratIntervalctx = $("#heartIntervalChart");


      var LineGraph = new Chart(ctx, {
        type: 'line',
        data: chartdata
      });
      var LineGraph = new Chart(heartRatectx, {
        type: 'line',
        data: heartRateData
      });
      var LineGraph = new Chart(heratIntervalctx, {
        type: 'line',
        data: heartIntervaldata
      });
    },
    error : function(data) {

    }
  });
}

ajaxFn();
//OR use BELOW line to wait 10 secs before first call
setInterval(ajaxFn, 1000);
