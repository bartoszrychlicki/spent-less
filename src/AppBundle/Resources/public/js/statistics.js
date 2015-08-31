$(function() {

    
    var chart_options = {

        ///Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines : true,
    
        //String - Colour of the grid lines
        scaleGridLineColor : "#D9D9D9",
    
        //Number - Width of the grid lines
        scaleGridLineWidth : 1,
    
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
    
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
    
        //Boolean - Whether the line is curved between points
        bezierCurve : true,
    
        //Number - Tension of the bezier curve between points
        bezierCurveTension : 0.4,
    
        //Boolean - Whether to show a dot for each point
        pointDot : true,
    
        //Number - Radius of each point dot in pixels
        pointDotRadius : 4,
    
        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth : 1,
    
        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius : 20,
    
        //Boolean - Whether to show a stroke for datasets
        datasetStroke : true,
    
        //Number - Pixel width of dataset stroke
        datasetStrokeWidth : 2,
    
        //Boolean - Whether to fill the dataset with a colour
        datasetFill : true,
    
        //String - A legend template
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
    
    };
    
    // ======================
    // Spendings by day Chart
    
    // Get context with jQuery - using jQuery's .get() method.
    var ctx = $("#expensesByDay").get(0).getContext("2d");
    
    var data = {
        labels: $("#expensesByDay").data('labels'),
        datasets: [
            {
                label: "Wydatki",
                fillColor: "#FAD9D9",
                strokeColor: "#F54E6D",
                pointColor: "#F54E6D",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(151,187,205,1)",            
                data: $("#expensesByDay").data('values')
            }
        ]
    }
    var dailyExpenseChart = new Chart(ctx).Line(data, chart_options);
    
    // ======================
    // Spendings by category
    
    // Get context with jQuery - using jQuery's .get() method.
    var ctx2 = $("#expensesByCategory").get(0).getContext("2d");
    
    var categoryExpensesChart = new Chart(ctx2).Pie($("#expensesByCategory").data("values"), {});
    
})