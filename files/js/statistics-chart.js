let currentDate = new Date().toISOString().slice(0, 10);
$("#weekInput").val(currentDate).attr("max", currentDate);
$("#weekInput").attr("data-bs-monday", getMonday(new Date()).toDateString());

function dateChange() {
    // Get date
    let dateInput = new Date($("#weekInput").val());
    
    // Check if date lies in the future
    if (dateInput > new Date()) return;

    // Get Monday of current week
    let dateInputWeek = getMonday(dateInput);


    // Check if Monday has changed with new input, and abort if not
    if (dateInputWeek.toDateString() == $("#weekInput").attr("data-bs-monday")) return;

    
    // Send data to PHP
    $.ajax({
        url: "../files/ajax/getChartData.php",
        type: "post",
        data: {"mondayDate": dateInputWeek.toISOString().slice(0, 10)},
        success: function(response) {
            alert(response);
        }
    });
}


function getMonday(dateInput) {
    let dateInputMonday = dateInput.getDate() - dateInput.getDay() + (dateInput.getDay() == 0 ? -6 : 1);
    return new Date(dateInput.setDate(dateInputMonday));
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
