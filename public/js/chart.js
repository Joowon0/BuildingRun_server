$(function() {
  /* ChartJS
   * -------
   * Data and config for chartjs
   */
  'use strict';
  var areaData = {
    labels: ["2013", "2014", "2015", "2016", "2017"],
    datasets: [{
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,
      fill: 'origin', // 0: fill to 'origin'
      fill: '+2', // 1: fill to dataset 3
      fill: 1, // 2: fill to dataset 1
      fill: false, // 3: no fill
      fill: '-2' // 4: fill to dataset 2
    }]
  };

  var areaOptions = {
    plugins: {
      filler: {
        propagate: true
      }
    }
  }


  if ($("#areaChart").length) {
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    var areaChart = new Chart(areaChartCanvas, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }
  if ($("#areaChart2").length) {
    var areaChartCanvas2 = $("#areaChart2").get(0).getContext("2d");
    var areaChart2 = new Chart(areaChartCanvas2, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }
  if ($("#areaChart3").length) {
    var areaChartCanvas3 = $("#areaChart3").get(0).getContext("2d");
    var areaChart3 = new Chart(areaChartCanvas3, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }
  if ($("#areaChart4").length) {
    var areaChartCanvas4 = $("#areaChart4").get(0).getContext("2d");
    var areaChart4 = new Chart(areaChartCanvas4, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }
  if ($("#areaChart5").length) {
    var areaChartCanvas5 = $("#areaChart5").get(0).getContext("2d");
    var areaChart5 = new Chart(areaChartCanvas5, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }

  if ($("#areaChart6").length) {
    var areaChartCanvas6 = $("#areaChart6").get(0).getContext("2d");
    var areaChart6 = new Chart(areaChartCanvas6, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }
  if ($("#areaChart7").length) {
    var areaChartCanvas7 = $("#areaChart7").get(0).getContext("2d");
    var areaChart7 = new Chart(areaChartCanvas7, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }
  if ($("#areaChart8").length) {
    var areaChartCanvas8 = $("#areaChart8").get(0).getContext("2d");
    var areaChart8 = new Chart(areaChartCanvas8, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }
});
