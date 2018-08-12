$(document).ready(function(){
  $.ajax({
    url : "/getJSON/airHouraqi",
    type : "GET",
    success : function(data){
      console.log(data);
      data = JSON.parse(data);

      var timestamp = [];
      var CO  = [];
      var SO2 = [];
      var NO2 = [];
      var O3  = [];
      var PM2_5 = [];
      var TEMP = [];

      for(var i in data) {
        ts = data[i].ts;
        timestamp.push(ts.substring(0,2) + 'd ' + ts.substring(3,5) + 'h ' + ts.substring(6) + '0m ');
        CO.push(data[i].CO);
        SO2.push(data[i].SO2);
        NO2.push(data[i].NO2);
        O3.push(Math.min(data[i].O3_1, data[i].O3_2));
        PM2_5.push(data[i].PM25);
        TEMP.push(data[i].TEMP);
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
          },
          {
            label: "Temperature",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(211, 72, 54, 0.75)",
            borderColor: "rgba(211, 72, 54, 1)",
            pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
            pointHoverBorderColor: "rgba(211, 72, 54, 1)",
            data: TEMP
          }
        ]
      };

      var COchartdata = {
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
          }
        ]
      };
      var SO2chartdata = {
        labels: timestamp,
        datasets: [
          {
            label: "SO2",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(211, 72, 54, 0.75)",
            borderColor: "rgba(211, 72, 54, 1)",
            pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
            pointHoverBorderColor: "rgba(211, 72, 54, 1)",
            data: SO2
          }
        ]
      };
      var NO2chartdata = {
        labels: timestamp,
        datasets: [
          {
            label: "NO2",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(59, 89, 152, 0.75)",
            borderColor: "rgba(59, 89, 152, 1)",
            pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
            pointHoverBorderColor: "rgba(59, 89, 152, 1)",
            data: NO2
          }
        ]
      };
      var O3chartdata = {
        labels: timestamp,
        datasets: [
          {
            label: "O3",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(29, 202, 255, 0.75)",
            borderColor: "rgba(29, 202, 255, 1)",
            pointHoverBackgroundColor: "rgba(29, 202, 255, 1)",
            pointHoverBorderColor: "rgba(29, 202, 255, 1)",
            data: O3
          }
        ]
      };
      var PMchartdata = {
        labels: timestamp,
        datasets: [
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
      var TEMPchartdata = {
        labels: timestamp,
        datasets: [
          {
            label: "Temperature",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(211, 72, 54, 0.75)",
            borderColor: "rgba(211, 72, 54, 1)",
            pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
            pointHoverBorderColor: "rgba(211, 72, 54, 1)",
            data: TEMP
          }
        ]
      };

      var ctx = $("#mycanvas");


      var coctx = $("#coChart");
      var so2ctx = $("#so2Chart");
      var no2ctx = $("#no2Chart");
      var o3ctx = $("#o3Chart");
      var pmctx = $("#pmChart");
      var tempctx = $("#tempChart");

      var LineGraph = new Chart(coctx, {
        type: 'line',
        data: COchartdata
      });
      var LineGraph = new Chart(so2ctx, {
        type: 'line',
        data: SO2chartdata
      });
      var LineGraph = new Chart(so2ctx, {
        type: 'line',
        data: SO2chartdata
      });
      var LineGraph = new Chart(no2ctx, {
        type: 'line',
        data: NO2chartdata
      });
      var LineGraph = new Chart(o3ctx, {
        type: 'line',
        data: O3chartdata
      });
      var LineGraph = new Chart(pmctx, {
        type: 'line',
        data: PMchartdata
      });
      var LineGraph = new Chart(tempctx, {
        type: 'line',
        data: TEMPchartdata
      });
    },
    error : function(data) {

    }
  });
});
