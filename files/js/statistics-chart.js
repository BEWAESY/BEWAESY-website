var eventCounterChartDataset = [];


let currentDate = new Date().toISOString().slice(0, 10);
$("#weekInput").val(currentDate).attr("max", currentDate);

// Call function to show initial chart of this week
dateChange();

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
            try {
                response = JSON.parse(response);
            } catch {}

            if (response[0] == "Success") {
                // Create the chart
                createChart(response[1]);

                // Set "data-bs-monday" attribute of weekInput
                $("#weekInput").attr("data-bs-monday", dateInputWeek.toDateString());
            } else {
                alert("Something went wrong");
            }
        }
    });
}


function getMonday(dateInput) {
    let dateInputMonday = dateInput.getDate() - dateInput.getDay() + (dateInput.getDay() == 0 ? -6 : 1);
    return new Date(dateInput.setDate(dateInputMonday));
}



function createChart(initialData) {
    $("#eventCounterChart").remove();
    $("#giesszeiten").append('<canvas class="my-4 w-100" id="eventCounterChart" width="900" height="380"></canvas>')

    let eventCounterChartDataset = [];

    Object.values(initialData).forEach(function(singleSystem) {
        let color = generateRandomColor();
        eventCounterChartDataset.push({
            label: singleSystem["name"],
            backgroundColor: color,
            borderColor: color,
            data: singleSystem["eventCounterData"],
        })
    });


    const eventCounterChartData = {
        datasets: eventCounterChartDataset
    };
    
    const eventCounterChartConfig = {
        type: 'bar',
        data: eventCounterChartData,
        options: {}
    };

    new Chart(
        document.getElementById('eventCounterChart'),
        eventCounterChartConfig
    );


    function generateRandomColor() {
        return `rgb(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)})`;
    }
}
