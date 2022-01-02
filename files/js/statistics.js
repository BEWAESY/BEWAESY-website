// Graphs
var ctx = document.getElementById('myChart')
// eslint-disable-next-line no-unused-vars
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
    labels: [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        "asdf"
    ],
    datasets: [{
        data: [
        0,
        1,
        2,
        3,
        4,
        5,
        6,
        7
        ],
        lineTension: 0,
        backgroundColor: 'transparent',
        borderColor: '#007bff',
        borderWidth: 4,
        pointBackgroundColor: '#007bff'
    }]
    },
    options: {
    scales: {
        yAxes: [{
        ticks: {
            beginAtZero: false
        }
        }]
    },
    legend: {
        display: false
    }
    }
})
