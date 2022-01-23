function addLog(timestamp, seconds, systemid) {
    $(`#tbody${systemid}`).append(`
        <tr>
            <td>${timestamp}</td>
            <td>${seconds}</td>
        </tr>
    `);
}












// Graphs
/*var ctx = document.getElementById('myChart')
// eslint-disable-next-line no-unused-vars


var data = {
    labels: [
        "-14",
        "-13",
        "-12",
        "-11",
        "-10",
        "-9",
        "-8",
        "-7",
        "-6",
        "-5",
        "-4",
        "-3",
        "-2",
        "gestern",
        "heute"
    ],
    datasets: [
        {
            label: "System 1",
            data: [
                "5",
                "10",
            ],
            //borderColor: Utils.CHART_COLORS.red,
            backgroundColor: "transperent",
        }
    ]
};

var myChart = new Chart(ctx, {
    type: "line",
    data: data,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: "top",
            },
            title: {
                display: true,
                text: "Gießzeiten von allen Bewässerungssystemen in den letzten 14 Tagen"
            }
        }
    },
});*/


/*var myChart = new Chart(ctx, {
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
        data: data,
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
})*/
