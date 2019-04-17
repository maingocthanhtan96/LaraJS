$(function() {
    /* DashboardJS
     * -------
     * Data and config for chartjs
     */
    'use strict';


    var areaMarketingData = {
        labels: ["2000", "2001", "2002", "2003", "2004", "2005", "2006", "2007", "2007", "2008", "2009", "2010", "2011", "2012", "2013", "2014", "2015", "2016", "2017", "2018"],
        datasets: [{
            label: '# of Votes',
            data: [60, 50, 55, 45, 58, 80, 60, 59, 70, 65, 24, 5, 15, 10, 30, 25, 35, 50, 40, 45],
            backgroundColor: [
                'rgba(2, 171, 254, 0.2)',
            ],
            pointBackgroundColor: 'rgba(255, 255, 255,1)',
            pointBorderColor: 'rgba(255, 255, 255,1)',
            borderColor: [
                'rgba(2, 171, 254,1)',
            ],
            borderWidth: 1,
            fill: true, // 3: no fill
        }]
    };

    var areaMarketingOptions = {
        scales: {
            yAxes: [{
                display: false,
                gridLines: {
                    display: false,
                    drawBorder: false,
                },
                ticks: {
                    beginAtZero: false,
                }
            }],
            xAxes: [{
                display: false,
            }],
        },
        legend: {
            display: false
        },
        plugins: {
            filler: {
                propagate: true
            },
        },
        elements: {
            line: {
                tension: 0
            },
            tooltips: {
                backgroundColor: 'rgba(31, 59, 179, 1)',
            }
        }
    }
    var purchaseDetailDatadata = {
        labels: ["Jan", "Feb", "Mar", "Apr"],
        datasets: [{
                label: 'Offline purchases',
                data: [41676, 40776, 41676, 39676],
                backgroundColor: [
                    'rgba(31, 59, 179, 1)',
                    'rgba(31, 59, 179, 1)',
                    'rgba(31, 59, 179, 1)',
                    'rgba(31, 59, 179, 1)',
                ],
                borderColor: [
                    'rgba(31, 59, 179, 1)',
                    'rgba(31, 59, 179, 1)',
                    'rgba(31, 59, 179, 1)',
                    'rgba(31, 59, 179, 1)',
                ],
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Online purchases',
                data: [40982, 50982, 31982, 48982],
                backgroundColor: [
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                ],
                borderColor: [
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                ],
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Offline sales',
                data: [57545, 37545, 87545, 48982],
                backgroundColor: [
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                ],
                borderColor: [
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                ],
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Online sales',
                data: [47545, 27545, 48982, 97545],
                backgroundColor: [
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                ],
                borderColor: [
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                ],
                borderWidth: 2,
                fill: false
            }
        ],
    };
    var purchaseDetailOptions = {
        scales: {
            xAxes: [{
                position: 'bottom',
                display: true,
                gridLines: {
                    display: false,
                    drawBorder: true,
                },
                ticks: {
                    display: false //this will remove only the label
                }
            }],
            yAxes: [{
                display: false,
                gridLines: {
                    drawBorder: true,
                    display: false,
                },
            }]
        },
        legend: {
            display: false
        },
        legendCallback: function(chart) {
            var text = [];
            text.push('<div class="row">');
            for (var i = 0; i < chart.data.datasets.length; i++) {
                text.push('<div class="col-sm-6 col mr-3 ml-3 ml-sm-0 mr-sm-0 pr-md-0"><div class="row mb-3 align-items-center"><div class="col-md-2"><span class="legend-label" style="background-color:' + chart.data.datasets[i].backgroundColor[i] + '"></span></div><div class="col-md-9 pl-md-2"><h3 class="mb-0">$ ' + chart.data.datasets[i].data[i] + '</h3></div><div class="col-sm-12"><p class="text-muted">' + chart.data.datasets[i].label + '</p></div></div>');
                text.push('</div>');
            }
            text.push('</div>');
            return text.join("");
        },
        tooltips: {
            backgroundColor: 'rgba(31, 59, 179, 1)',
        }

    };

    var purchaseDetailDatadataDark = {
        labels: ["Jan", "Feb", "Mar", "Apr"],
        datasets: [{
                label: 'Offline purchases',
                data: [41676, 40776, 41676, 39676],
                backgroundColor: [
                    'rgba(0,24,255, 1)',
                    'rgba(0,24,255, 1)',
                    'rgba(0,24,255, 1)',
                    'rgba(0,24,255, 1)',
                ],
                borderColor: [
                    'rgba(0,24,255, 1)',
                    'rgba(0,24,255, 1)',
                    'rgba(0,24,255, 1)',
                    'rgba(0,24,255, 1)',
                ],
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Online purchases',
                data: [40982, 50982, 31982, 48982],
                backgroundColor: [
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                ],
                borderColor: [
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                    'rgba(35, 175, 71, 1)',
                ],
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Offline sales',
                data: [57545, 37545, 87545, 48982],
                backgroundColor: [
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                ],
                borderColor: [
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                ],
                borderWidth: 2,
                fill: false
            },
            {
                label: 'Online sales',
                data: [47545, 27545, 48982, 97545],
                backgroundColor: [
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                ],
                borderColor: [
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                    'rgba(224, 224, 224, 1)',
                ],
                borderWidth: 2,
                fill: false
            }
        ],
    };
    var purchaseDetailOptionsDark = {
        scales: {
            xAxes: [{
                position: 'bottom',
                display: true,
                gridLines: {
                    display: false,
                    drawBorder: true,
                },
                ticks: {
                    display: false //this will remove only the label
                }
            }],
            yAxes: [{
                display: false,
                gridLines: {
                    drawBorder: true,
                    display: false,
                },
            }]
        },
        legend: {
            display: false
        },
        legendCallback: function(chart) {
            var text = [];
            text.push('<div class="row">');
            for (var i = 0; i < chart.data.datasets.length; i++) {
                text.push('<div class="col-sm-6 col mr-3 ml-3 ml-sm-0 mr-sm-0 pr-md-0"><div class="row mb-3 align-items-center"><div class="col-md-2"><span class="legend-label" style="background-color:' + chart.data.datasets[i].backgroundColor[i] + '"></span></div><div class="col-md-9 pl-md-2"><h3 class="mb-0">$ ' + chart.data.datasets[i].data[i] + '</h3></div><div class="col-sm-12"><p class="text-muted">' + chart.data.datasets[i].label + '</p></div></div>');
                text.push('</div>');
            }
            text.push('</div>');
            return text.join("");
        },
        tooltips: {
            backgroundColor: 'rgba(31, 59, 179, 1)',
        }

    };


    var salesData = {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
        datasets: [{
                label: 'first label',
                data: [20, 25, 35, 45, 35, 40, 46, 44],
                backgroundColor: [
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                ],
                borderColor: [
                    'rgba(35, 175, 71, 1)'
                ],
                borderWidth: 4,
                fill: false,
                pointBorderWidth: 4,
                pointRadius: [0, 0, 0, 5, 0],
                pointHoverRadius: [0, 0, 0, 5, 0],
                pointBackgroundColor: ['rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)'],
                pointBorderColor: ['rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)'],
            },
            {
                label: 'second label',
                data: [30, 24, 28, 35, 40, 35, 32, 38],
                borderColor: [
                    'rgba(31, 59, 179, 1)',
                ],
                borderWidth: 4,
                fill: false,
                pointBorderWidth: 4,
                pointRadius: [0, 0, 0, 0, 0],
                pointHoverRadius: [0, 0, 0, 0, 0],
                pointBackgroundColor: ['rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)'],
                pointBorderColor: ['rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)'],
            },
            {
                label: 'third label',
                data: [35, 30, 36, 45, 35, 44, 48, 53],
                borderColor: [
                    'rgba(232, 233, 241,  .48)',
                ],
                backgroundColor: [
                    'rgba(232,233,241, .48)',
                ],
                borderWidth: 4,
                fill: true,
                pointBorderWidth: 4,
                pointRadius: [0, 0, 0, 0, 0],
                pointHoverRadius: [0, 0, 0, 0, 0],
                pointBackgroundColor: ['rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)'],
                pointBorderColor: ['rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)'],
            }
        ],
    };

    var salesDataOptions = {
        scales: {
            yAxes: [{
                display: false
            }],
            xAxes: [{
                position: 'bottom',
                gridLines: {
                    drawBorder: false,
                    display: false,
                },
                ticks: {
                    beginAtZero: false,
                    stepSize: 50
                }
            }],

        },
        legend: {
            display: false,
        },
        elements: {
            point: {
                radius: 0
            }
        },
        tooltips: {
            backgroundColor: 'rgba(2, 171, 254, 1)',
        }
    };
    var salesDataDark = {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
        datasets: [{
                label: 'first label',
                data: [20, 25, 35, 45, 35, 40, 46, 44],
                backgroundColor: [
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                    'rgba(2, 171, 254, 1)',
                ],
                borderColor: [
                    'rgba(35, 175, 71, 1)'
                ],
                borderWidth: 4,
                fill: false,
                pointBorderWidth: 4,
                pointRadius: [0, 0, 0, 5, 0],
                pointHoverRadius: [0, 0, 0, 5, 0],
                pointBackgroundColor: ['rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)'],
                pointBorderColor: ['rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)'],
            },
            {
                label: 'second label',
                data: [30, 24, 28, 35, 40, 35, 32, 38],
                borderColor: [
                    'rgba(31, 59, 179, 1)',
                ],
                borderWidth: 4,
                fill: false,
                pointBorderWidth: 4,
                pointRadius: [0, 0, 0, 0, 0],
                pointHoverRadius: [0, 0, 0, 0, 0],
                pointBackgroundColor: ['rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)'],
                pointBorderColor: ['rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)'],
            },
            {
                label: 'third label',
                data: [35, 30, 36, 45, 35, 44, 48, 53],
                borderColor: [
                    'rgba(28,30,47, 1)',
                ],
                backgroundColor: [
                    'rgba(28,30,47, 1)',
                ],
                borderWidth: 4,
                fill: true,
                pointBorderWidth: 4,
                pointRadius: [0, 0, 0, 0, 0],
                pointHoverRadius: [0, 0, 0, 0, 0],
                pointBackgroundColor: ['rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)', 'rgba(35, 175, 71,1)'],
                pointBorderColor: ['rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)'],
            }
        ],
    };
    var salesDataOptionsDark = {
        scales: {
            yAxes: [{
                display: false
            }],
            xAxes: [{
                position: 'bottom',
                gridLines: {
                    drawBorder: false,
                    display: false,
                },
                ticks: {
                    beginAtZero: false,
                    stepSize: 50
                }
            }],

        },
        legend: {
            display: false,
        },
        elements: {
            point: {
                radius: 0
            }
        },
        tooltips: {
            backgroundColor: 'rgba(2, 171, 254, 1)',
        }
    };

    var acquisitionBarOption = {
        layout: {
            padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: 0
            }
        },

        scales: {
            responsive: true,
            maintainAspectRatio: true,
            yAxes: [{
                stacked: true,
                display: false,
                gridLines: {
                    color: 'rgba(0, 0, 0, 0.03)',
                }
            }],
            xAxes: [{
                stacked: true,
                display: false,
                barPercentage: 0.9,
                gridLines: {
                    display: false,
                }
            }]
        },
        legend: {
            display: false
        }
    };
    if ($('#acquisition-bar_1').length) {
        var acquisitionBar1 = $("#acquisition-bar_1").get(0).getContext("2d");
        var barChart = new Chart(acquisitionBar1, {
            type: 'bar',
            data: {
                labels: ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5"],
                datasets: [{
                    data: [39, 39, 48, 28, 38],
                    backgroundColor: '#fff'
                }]
            },
            options: acquisitionBarOption
        });
    }
    if ($('#acquisition-bar_2').length) {
        var acquisitionBar2 = $("#acquisition-bar_2").get(0).getContext("2d");
        var barChart = new Chart(acquisitionBar2, {
            type: 'bar',
            data: {
                labels: ["Day 1", "Day 2", "Day 3", "Day 4", "Day 5"],
                datasets: [{
                    data: [43, 24, 32, 15, 20],
                    backgroundColor: '#fff'
                }]
            },
            options: acquisitionBarOption
        });
    }
    if ($('#circleProgress1').length) {
        var bar = new ProgressBar.Circle(circleProgress1, {
            color: '#0aadfe',
            strokeWidth: 10,
            trailWidth: 10,
            easing: 'easeInOut',
            duration: 1400,
            width: 42,
        });
        bar.animate(.18); // Number from 0.0 to 1.0
    }
    if ($('#circleProgress2').length) {
        var bar = new ProgressBar.Circle(circleProgress2, {
            color: '#fa424a',
            strokeWidth: 10,
            trailWidth: 10,
            easing: 'easeInOut',
            duration: 1400,
            width: 42,

        });
        bar.animate(.36); // Number from 0.0 to 1.0
    }

    // Get context with jQuery - using jQuery's .get() method.

    if ($("#salesTopChart").length) {
        var graphGradient = document.getElementById("salesTopChart").getContext('2d');;
        var saleGradientBg = graphGradient.createLinearGradient(25, 0, 75, 150);
        saleGradientBg.addColorStop(0, 'rgba(8, 239, 185, 0.1)');
        saleGradientBg.addColorStop(1, 'rgba(228, 243, 240, 0.5)');
        var salesTopData = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May"],
            datasets: [{
                label: '# of Votes',
                data: [0, 30, 20, 70, 75],
                backgroundColor: saleGradientBg,
                borderColor: [
                    'rgba(35, 175, 71,1)',
                ],
                borderWidth: 3,
                fill: true, // 3: no fill
                pointBorderWidth: 4,
                pointRadius: [0, 0, 0, 10, 0],
                pointHoverRadius: [0, 0, 0, 10, 0],
                pointBackgroundColor: ['rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)'],
                pointBorderColor: ['rgba(35, 175, 71,0.3)', 'rgba(35, 175, 71,0.3)', 'rgba(35, 175, 71,0.3)', 'rgba(35, 175, 71,0.3)'],
            }]
        };
    
        var salesTopOptions = {
            scales: {
                yAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                        beginAtZero: false,
                    }
                }],
            },
            legend: {
                display: false
            },
            elements: {
                line: {
                    tension: 0.4,
                }
            },
            tooltips: {
                backgroundColor: 'rgba(31, 59, 179, 1)',
            }
        }
        var salesTop = new Chart(graphGradient, {
            type: 'line',
            data: salesTopData,
            options: salesTopOptions
        });
    }
    if ($("#salesTopChartDark").length) {
    var graphGradientTop = document.getElementById('salesTopChartDark').getContext('2d');
    var canvas = document.getElementById("salesTopChartDark");
    var saleGradientBgTop = graphGradientTop.createLinearGradient(25, 0, 75, 150);
    saleGradientBgTop.addColorStop(0, 'rgba(8, 239, 185, 0.1)');
    saleGradientBgTop.addColorStop(1, 'rgba(228, 243, 240, 0.9)');

    var salesTopDataDark = {
        labels: ["Jan", "Feb", "Mar", "Apr", "May"],
        datasets: [{
            label: '# of Votes',
            data: [0, 30, 20, 70, 75],
            backgroundColor: graphGradientTop,
            borderColor: [
                'rgba(35, 175, 71,1)',
            ],
            borderWidth: 3,
            fill: true, // 3: no fill
            pointBorderWidth: 4,
            pointRadius: [0, 0, 0, 10, 0],
            pointHoverRadius: [0, 0, 0, 10, 0],
            pointBackgroundColor: ['rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)', 'rgba(255, 255, 255,1)'],
            pointBorderColor: ['rgba(35, 175, 71,0.3)', 'rgba(35, 175, 71,0.3)', 'rgba(35, 175, 71,0.3)', 'rgba(35, 175, 71,0.3)'],
        }]
    };

    var salesTopOptionsDark = {
        scales: {
            yAxes: [{
                gridLines: {
                    display: false,
                    drawBorder: false,
                },
                ticks: {
                    beginAtZero: false,
                }
            }],
        },
        legend: {
            display: false
        },
        elements: {
            line: {
                tension: 0.4,
            }
        },
        tooltips: {
            backgroundColor: 'rgba(31, 59, 179, 1)',
        }
    }
        var areaChartCanvas = $("#salesTopChartDark").get(0).getContext("2d");
        var salesTop = new Chart(areaChartCanvas, {
            type: 'line',
            data: salesTopDataDark,
            options: salesTopOptionsDark
        });
    }

    if ($("#areaChartMarketing").length) {
        var areaChartCanvas = $("#areaChartMarketing").get(0).getContext("2d");
        var areaChartMarketing = new Chart(areaChartCanvas, {
            type: 'line',
            data: areaMarketingData,
            options: areaMarketingOptions
        });
    }

    if ($("#purchaseDetails").length) {
        var barChartCanvas = $("#purchaseDetails").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var barChart = new Chart(barChartCanvas, {
            type: 'horizontalBar',
            data: purchaseDetailDatadata,
            options: purchaseDetailOptions
        });
        document.getElementById('chart-legends-purchase').innerHTML = barChart.generateLegend();
    }
    if ($("#purchaseDetailsDark").length) {
        var barChartCanvas = $("#purchaseDetailsDark").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var barChart = new Chart(barChartCanvas, {
            type: 'horizontalBar',
            data: purchaseDetailDatadataDark,
            options: purchaseDetailOptionsDark
        });
        document.getElementById('chart-legends-purchaseDark').innerHTML = barChart.generateLegend();
    }
    if ($("#salesChart").length) {
        var lineChartCanvas = $("#salesChart").get(0).getContext("2d");
        var saleschart = new Chart(lineChartCanvas, {
            type: 'line',
            data: salesData,
            options: salesDataOptions
        });
    }
    if ($("#salesChartDark").length) {
        var lineChartCanvasDark = $("#salesChartDark").get(0).getContext("2d");
        var saleschartdark = new Chart(lineChartCanvasDark, {
            type: 'line',
            data: salesDataDark,
            options: salesDataOptionsDark
        });
    }

    if ($("#flotChart").length) {
        var d1 = [
            [0,56.04],
            [1, 56],
            [2, 55],
            [3, 59],
            [4, 59],
            [5, 59],
            [6, 57],
            [7, 56],
            [8, 57],
            [9, 54],
            [10, 56],
            [11, 58],
            [12, 57],
            [13, 59],
            [14, 58],
            [15, 59],
            [16, 57],
            [17, 55],
            [18, 56],
            [19, 54],
            [20, 52],
            [21, 49],
            [22, 48],
            [23, 50],
            [24, 50],
            [24, 46],
            [26, 45],
            [29, 49],
            [30, 50],
            [31, 52],
            [32, 53],
            [33, 52],
            [34, 55],
            [35, 54],
            [36, 53],
            [37, 56],
            [38, 55],
            [39, 56],
            [40, 55],
            [41, 54],
            [42, 55],
            [43, 57],
            [44, 58],
            [45, 56],
            [46, 55],
            [47, 56],
            [48, 57],
            [49, 58],
            [50, 59],
            [51, 58],
            [52, 57],
            [53, 55],
            [54, 53],
            [55, 52],
            [56, 55],
            [57, 57],
            [58, 55],
            [59, 54],
            [60, 52],
            [61, 55],
            [62, 57],
            [63, 56],
            [64, 57],
            [65, 58],
            [66, 59],
            [67, 58],
            [68, 59],
            [69, 57],
            [70, 56],
            [71, 55],
            [72, 57],
            [73, 58],
            [74, 59],
            [75, 60],
            [76, 62],
            [77, 60],
            [78, 59],
            [79, 58],
            [80, 57],
            [81, 56],
            [82, 57],
            [83, 56],
            [84, 58],
            [85, 59],
            
        ];
        var d2 = [
            [0,32.68],
            [1, 32],
            [2, 35],
            [3, 39],
            [4, 39],
            [5, 39],
            [6, 37],
            [7, 36],
            [8, 37],
            [9, 34],
            [10, 36],
            [11, 38],
            [12, 37],
            [13, 39],
            [14, 38],
            [15, 39],
            [16, 37],
            [17, 35],
            [18, 36],
            [19, 34],
            [20, 30],
            [21, 28],
            [22, 31],
            [23, 29],
            [24, 27],
            [24, 24],
            [26, 23],
            [29, 26],
            [30, 25],
            [31, 27],
            [32, 28],
            [33, 29],
            [34, 32],
            [35, 30],
            [36, 33],
            [37, 31],
            [38, 35],
            [39, 34],
            [40, 32],
            [41, 35],
            [42, 37],
            [43, 35],
            [44, 36],
            [45, 34],
            [46, 30],
            [47, 28],
            [48, 28],
            [49, 28],
            [50, 32],
            [51, 29],
            [52, 33],
            [53, 35],
            [54, 33],
            [55, 32],
            [56, 35],
            [57, 37],
            [58, 35],
            [59, 34],
            [60, 32],
            [61, 35],
            [62, 37],
            [63, 36],
            [64, 37],
            [65, 38],
            [66, 39],
            [67, 38],
            [68, 39],
            [69, 37],
            [70, 36],
            [71, 35],
            [72, 37],
            [73, 38],
            [74, 39],
            [75, 36],
            [76, 37],
            [77, 35],
            [78, 39],
            [79, 38],
            [80, 37],
            [81, 36],
            [82, 37],
            [83, 36],
            [84, 38],
            [85, 39],
        ];
  
        var curvedLineOptions = {
            series: {
                curvedLines: {
                    active: true,
                },
                shadowSize: 0,
                lines: {
                    show: true,
                    lineWidth: 3,
                    fill: false
                },
            },
            
            grid: {
                borderWidth: 0,
                labelMargin: 0
            },
            yaxis: {
                show: false,
                min: 0,
                max: 80,
                position: "left",
                ticks: [
                    [0, '600'],
                    [50, '610'],
                    [100, '620'],
                    [150, '640']
                ],
                tickColor: 'rgba(132,132,132,0.17)',
                tickLength:0,
            },
            xaxis: {
                show: true,
                position: "top",
                ticks: [
                    [0, 'H'],
                    [7, 'L'],
                    [14, 'B'],
                    [21, 'N'],
                    [28, 'N'],
                    [35, 'M'],
                    [41, 'A'],
                    [48, 'S'],
                    [56, 'C'],
                    [63, 'D'],
                    [70, 'E'],
                    [77, 'Q'],
                    [84, 'T']
                ],
                tickColor: 'rgba(132,132,132,0.17)'
            },
            legend: {
                noColumns: 4,
                container: $("#legendContainer"),
                labelFormatter: legendFormatter,
            }
        }
    $.plot($("#flotChart"), [{
        data: d1,
        curvedLines: {
            apply: true ,
            tension: 1,
        },
        points: {
            show: false,
            fillColor: '#1f3bb3',
        },
        color: '#1f3bb3',
        lines: {
            show: true, 
            fill: false,
        },
        label: 'This year',
        stack: true,
    },
    {
        data: d2,
        curvedLines: {
            apply: true 
        },
        color: '#23af47',
        label: 'Past year',
        stack: true,
    }
], curvedLineOptions);
    function legendFormatter(label, series) {
        return '<h6>' + label + '</h6><h2> ' + series.data[80] + '</h2><div>'+ series.data[0] +' % Total</div>';
    };
}
if ($("#flotChartDark").length) {
    var d1 = [
        [0,56.04],
        [1, 56],
        [2, 55],
        [3, 59],
        [4, 59],
        [5, 59],
        [6, 57],
        [7, 56],
        [8, 57],
        [9, 54],
        [10, 56],
        [11, 58],
        [12, 57],
        [13, 59],
        [14, 58],
        [15, 59],
        [16, 57],
        [17, 55],
        [18, 56],
        [19, 54],
        [20, 52],
        [21, 49],
        [22, 48],
        [23, 50],
        [24, 50],
        [24, 46],
        [26, 45],
        [29, 49],
        [30, 50],
        [31, 52],
        [32, 53],
        [33, 52],
        [34, 55],
        [35, 54],
        [36, 53],
        [37, 56],
        [38, 55],
        [39, 56],
        [40, 55],
        [41, 54],
        [42, 55],
        [43, 57],
        [44, 58],
        [45, 56],
        [46, 55],
        [47, 56],
        [48, 57],
        [49, 58],
        [50, 59],
        [51, 58],
        [52, 57],
        [53, 55],
        [54, 53],
        [55, 52],
        [56, 55],
        [57, 57],
        [58, 55],
        [59, 54],
        [60, 52],
        [61, 55],
        [62, 57],
        [63, 56],
        [64, 57],
        [65, 58],
        [66, 59],
        [67, 58],
        [68, 59],
        [69, 57],
        [70, 56],
        [71, 55],
        [72, 57],
        [73, 58],
        [74, 59],
        [75, 60],
        [76, 62],
        [77, 60],
        [78, 59],
        [79, 58],
        [80, 57],
        [81, 56],
        [82, 57],
        [83, 56],
        [84, 58],
        [85, 59],
        
    ];
    var d2 = [
        [0,32.68],
        [1, 32],
        [2, 35],
        [3, 39],
        [4, 39],
        [5, 39],
        [6, 37],
        [7, 36],
        [8, 37],
        [9, 34],
        [10, 36],
        [11, 38],
        [12, 37],
        [13, 39],
        [14, 38],
        [15, 39],
        [16, 37],
        [17, 35],
        [18, 36],
        [19, 34],
        [20, 30],
        [21, 28],
        [22, 31],
        [23, 29],
        [24, 27],
        [24, 24],
        [26, 23],
        [29, 26],
        [30, 25],
        [31, 27],
        [32, 28],
        [33, 29],
        [34, 32],
        [35, 30],
        [36, 33],
        [37, 31],
        [38, 35],
        [39, 34],
        [40, 32],
        [41, 35],
        [42, 37],
        [43, 35],
        [44, 36],
        [45, 34],
        [46, 30],
        [47, 28],
        [48, 28],
        [49, 28],
        [50, 32],
        [51, 29],
        [52, 33],
        [53, 35],
        [54, 33],
        [55, 32],
        [56, 35],
        [57, 37],
        [58, 35],
        [59, 34],
        [60, 32],
        [61, 35],
        [62, 37],
        [63, 36],
        [64, 37],
        [65, 38],
        [66, 39],
        [67, 38],
        [68, 39],
        [69, 37],
        [70, 36],
        [71, 35],
        [72, 37],
        [73, 38],
        [74, 39],
        [75, 36],
        [76, 37],
        [77, 35],
        [78, 39],
        [79, 38],
        [80, 37],
        [81, 36],
        [82, 37],
        [83, 36],
        [84, 38],
        [85, 39],
    ];

    var curvedLineOptionsDark = {
        series: {
            curvedLines: {
                active: true,
            },
            shadowSize: 0,
            lines: {
                show: true,
                lineWidth: 3,
                fill: false
            },
        },
        
        grid: {
            borderWidth: 0,
            labelMargin: 0
        },
        yaxis: {
            show: false,
            min: 0,
            max: 80,
            position: "left",
            ticks: [
                [0, '600'],
                [50, '610'],
                [100, '620'],
                [150, '640']
            ],
            tickColor: 'rgba(132,132,132,0.17)',
            tickLength:0,
        },
        xaxis: {
            show: true,
            position: "top",
            ticks: [
                [0, 'H'],
                [7, 'L'],
                [14, 'B'],
                [21, 'N'],
                [28, 'N'],
                [35, 'M'],
                [41, 'A'],
                [48, 'S'],
                [56, 'C'],
                [63, 'D'],
                [70, 'E'],
                [77, 'Q'],
                [84, 'T']
            ],
            tickColor: 'rgba(132,132,132,0.17)'
        },
        legend: {
            noColumns: 4,
            container: $("#legendContainer"),
            labelFormatter: legendFormatter,
        }
    }
$.plot($("#flotChartDark"), [{
    data: d1,
    curvedLines: {
        apply: true ,
        tension: 1,
    },
    points: {
        show: false,
        fillColor: '#fff',
    },
    color: '#fff',
    lines: {
        show: true, 
        fill: false,
    },
    label: 'This year',
    stack: true,
},
{
    data: d2,
    curvedLines: {
        apply: true 
    },
    color: '#23af47',
    label: 'Past year',
    stack: true,
}
], curvedLineOptionsDark);
function legendFormatter(label, series) {
    return '<h6>' + label + '</h6><h2 class="text-white"> ' + series.data[80] + '</h2><div>'+ series.data[0] +' % Total</div>';
};
}
});
/*--------------------------------
   ----- REALTIME CHART -----
   --------------------------------*/