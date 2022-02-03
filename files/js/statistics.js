function addLog(timestamp, seconds, systemid) {
    $(`#tbody${systemid}`).append(`
        <tr>
            <td>${timestamp} Uhr</td>
            <td>${seconds}</td>
        </tr>
    `);
}


function loadAdditionalLogData(systemid) {
    // Disable button and add loading animation
    $(`#moreDataButton${systemid}`).prop("disabled", true).prepend('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ');

    // Get number of rows
    let records = $(`#tbody${systemid} tr`).length;

    $.ajax({
        url: "../files/ajax/getAdditionalSystems.php",
        type: "post",
        data: {"systemId": systemid, "records": records},
        success: function(response) {
            let data = JSON.parse(response);

            if (data.length < 5) {
                // Hide "Mehr laden" button
                $(`#moreDataButton${systemid}`).hide();
            } 
            
            if (data.length) {
                for (entry of data) {
                    // Get right date
                    let date = new Date(entry["timestamp"]);

                    let day = (`0${date.getDate()}`).slice(-2);
                    let month = (`0${date.getMonth() + 1}`).slice(-2);
                    let year = date.getFullYear();
                    let hours = (`0${date.getHours()}`).slice(-2);
                    let minutes = (`0${date.getMinutes()}`).slice(-2);

                    let formattedDate = `${day}.${month}.${year} ${hours}:${minutes}`;


                    addLog(formattedDate, entry["seconds"], systemid);
                }
            }

            // Reset "Mehr laden" button
            $(`#moreDataButton${systemid}`).prop("disabled", false).find("span").remove();
        }
    });
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
