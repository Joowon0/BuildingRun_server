$(document).ready(function(){
  $.ajax({
    url : "/getJSON/heart10Min",
    type : "GET",
    success : function(data){
      //console.log(data);
      data = JSON.parse(data);

      var timestamp = [];
      var heartRate  = [];
      var heartInterval = [];

      for(var i in data) {
        ts = data[i].ts;
        h = ts.substring(3,5);
        if (h < 12) {
          hour = h;
          stamp = " AM";
        }
        else {
          hour = (h - 12);
          stamp = " PM";
        }

        timestamp.push(hour + ':' + ts.substring(6) + '0' + stamp);
        heartRate.push(data[i].HeartRate);
        heartInterval.push(data[i].HeartInterval);
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
      var heartRatectx = $("#heartRate10MinChart");
      var heratIntervalctx = $("#heartInterval10MinChart");


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
});
