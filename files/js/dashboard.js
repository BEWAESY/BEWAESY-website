// Define basic values
// Define grids for triggers
const triggerBasicGrid = "45px 165px";

// Time grid
const timeGrid = triggerBasicGrid + " 100px 26px";

// Temperature + humidity grid
const triggerTemperature1_grid = triggerBasicGrid + " 117px";  // Add value for second select
const triggerTemparature2_normalGrid = triggerTemperature1_grid + " 90px 16px";  // Grid that is used if values "smaller" or "bigger" are chosen
//const triggerTemperature2_specialGrid;  // Grid that is used if value "between" is chosen

var newCounter = 0;



// Save changed system settings
$(document).ready(function() {
    $("form").submit(function() {
        let systemid = $(this).attr("id");

        alert(systemid);

        let data = $(this).serialize();

        alert(data);
        alert($(this).attr("id"));
    });
});



function changeTrigger(triggerId, valuePlace) {
    // Check what value was changed
    if (valuePlace == 1) {
        let inputValue = $(`#changeTrigger${triggerId}`).val();  // Get value from input


        // Remove older styles
        $(`#triggerSecondInput${triggerId}`).empty();
        $(`#triggerThirdInput${triggerId}`).empty();
        $(`#unit1_${triggerId}`).empty();


        // Check the value and then edit page accordingly
        if (inputValue == "time") {
            // Change grid
            $(`#trigger${triggerId}`).css("grid-template-columns", timeGrid);

            // Insert new time select
            $(`#triggerSecondInput${triggerId}`).append(`
                <input id="triggerSecondValue${triggerId}" type="time" class="form-control">
            `);
            $(`#triggerThirdInput${triggerId}`).append("Uhr");

            // Focus on time input
            $(`#triggerSecondValue${triggerId}`).focus();
        } else if (inputValue == "temperature" || inputValue == "humidity") {
            // Change grid to fit content
            $(`#trigger${triggerId}`).css("grid-template-columns", triggerTemperature1_grid);

            // Insert new select
            $(`#triggerSecondInput${triggerId}`).append(`
                <select id="triggerSecondValue${triggerId}" onchange="changeTrigger('${triggerId}', 2);" class="form-select" aria-label="Auswählen, ob Aktion ausgeführt werden soll, wenn Wert kleiner, größer oder gleich angegebenem Wert.">
                    <option selected></option>
                    <option value="smaller">kleiner</option>
                    <option value="bigger">größer</option>
                    <option value="equal" disabled>zwischen</option>
                </select>`
            );
            $(`#triggerSecondValue${triggerId}`).focus();  // Focus on new select
        }
    } else if (valuePlace == 2) {
        let inputValue = $(`#triggerSecondValue${triggerId}`).val();
        let unit;
        let minValue;
        let maxValue;
        
        // Check if temperature or humidity was selected to display right units
        if ($(`#changeTrigger${triggerId}`).val() == "temperature") {
            unit = "°C";
            minValue = -20;
            maxValue = 60;
        } else {
            unit = "%";
            minValue = 5;
            maxValue = 95;
        }

        // Remove old input
        $(`#triggerThirdInput${triggerId}`).empty();
        $(`#unit1_${triggerId}`).empty();

        
        // Check value
        if (inputValue == "smaller" || inputValue == "bigger") {
            // Change grid
            $(`#trigger${triggerId}`).css("grid-template-columns", triggerTemparature2_normalGrid);

            // Append input box
            $(`#triggerThirdInput${triggerId}`).append(`
                <input id="triggerThirdValue${triggerId}" type="number" class="form-control" min="${minValue}" max="${maxValue}">
            `);
            $(`#unit1_${triggerId}`).append(unit)
            $(`#triggerThirdValue${triggerId}`).focus();
        } else if (inputValue == "between") {
            // TODO
        }
    }
}


function addTrigger(id, systemid) {
    // Check if id exists, if not generate new one
    if (id == "") id = `new${++newCounter}`;

    $(`#addTriggers${systemid}`).append(`
        <div id="triggerCard${id}" class="card mb-3">
            <div class="card-body trigger-body">
                <div id="trigger${id}" class="trigger-card">
                    <b>Wenn</b>
                    <select id="changeTrigger${id}" onchange="changeTrigger('${id}', 1);" class="form-select">
                        <option></option>
                        <option value="time">Uhrzeit</option>
                        <option value="temperature">Temperatur</option>
                        <option value="humidity">Luftfeuchtigkeit</option>
                    </select>

                    <div id="triggerSecondInput${id}"></div>

                    <div id="triggerThirdInput${id}"></div>

                    <div id="unit1_${id}"></div>
                </div>

                <b>dann:</b>

                <div id="action${id}" class="trigger-action">
                    gieße für <input id="waterSeconds${id}" type="number" class="form-control" min="1"> Sekunden
                </div>

                <button type="button" onclick="removeTrigger('${id}')" class="btn btn-outline-danger btn-sm">Entfernen</button>
            </div>
        </div>
    `);
}


function removeTrigger(id) {
    $(`#triggerCard${id}`).remove();
}



function createTriggers(triggerData) {
    // Get values
    let id = triggerData["id"];
    let systemid = triggerData["systemid"];
    let eventTrigger = triggerData["eventTrigger"];
    let triggerValue1 = triggerData["triggerValue1"];
    let triggerValue2 = triggerData["triggerValue2"];
    let triggerRange = triggerData["triggerRange"];
    let seconds = triggerData["seconds"];

    // Add new trigger
    addTrigger(id, systemid);

    // INSERT DATA
    // Insert seconds
    $(`#waterSeconds${id}`).val(seconds);

    // Choose right select
    $(`#changeTrigger${id}`).val(eventTrigger);
    changeTrigger(id, 1);  // Execute function to change trigger

    // If time, insert right time
    if (eventTrigger == "time") {
        $(`#triggerSecondValue${id}`).val(triggerValue1).blur();
    }

    // If temperature or humidity, insert right values
    if (eventTrigger == "temperature" || eventTrigger == "humidity") {
        $(`#triggerSecondValue${id}`).val(triggerRange);
        changeTrigger(id, 2);
        $(`#triggerThirdValue${id}`).val(triggerValue1).blur();
    }
}
