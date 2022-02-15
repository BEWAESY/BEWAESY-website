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

    let colorCycle = 0;

    Object.values(initialData).forEach(function(singleSystem) {
        if (colorCycle >= 6) colorCycle = 0;  // Reset counter if all colors are already used

        let color = generateChartColor(colorCycle++);

        eventCounterChartDataset.push({
            label: singleSystem["name"],
            borderColor: color[1],
            backgroundColor: color[0],
            data: singleSystem["eventCounterData"],
        })
    });

    const eventCounterChartData = {
        datasets: eventCounterChartDataset
    };
    
    const eventCounterChartConfig = {
        type: 'bar',
        data: eventCounterChartData,
        options: {
            elements: {
                bar: {
                    borderWidth: 2,
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: "Auslöser"
                },
                subtitle: {
                    display: true,
                    text: "Zeigt an, wie oft ein Bewässerungssystem an einem Tag gegossen wurde"
                }
            }
        }
    };

    new Chart(
        document.getElementById('eventCounterChart'),
        eventCounterChartConfig
    );
}

function generateChartColor(colorCycle) {
    let backgroundColors = ["#ffb1c1", "#9ad0f5", "#ffe6aa", "#a5dfdf", "#ccb2ff", "#e4e5e7"];
    let borderColors = ["#ff8ba3", "#36a2eb", "#ffcd56", "#4bc0c0", "#9966ff", "#c9cbcf"];
    return [backgroundColors[colorCycle], borderColors[colorCycle]];
}


// Show the chart from last week
function weekLast(inputId) {
    // Get required data
    let dateInput = new Date($(inputId).val());

    // Subtract 7 days from the current date
    dateInput.setDate(dateInput.getDate() - 7);

    // Convert the date into the right format for the input and insert it
    $(inputId).val(dateInput.toISOString().slice(0, 10));
}

// Show the chart from next week
function weekNext(inputId) {
    // Get required data
    let dateInput = new Date($(inputId).val());

    // Add 7 days to the current date
    dateInput.setDate(dateInput.getDate() + 7);

    // Check if the date lies in the future, and if yes, go to this day
    if (dateInput > new Date()) dateInput = new Date();

    // Convert the date into the right format for the input and insert it
    $(inputId).val(dateInput.toISOString().slice(0, 10));
}
