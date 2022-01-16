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
var triggerIds = [];



// Save changed system settings
$(document).ready(function() {
    $("form").submit(function() {
        let systemid = $(this).attr("id");

        // Get cooldown and max Seconds
        let cooldown = $(`#cooldown${systemid}`).val();
        let maxSeconds = $(`#maxSeconds${systemid}`).val();

        // organize data
        let sendData = [];
        let systemData = {
            "id": systemid,
            "cooldown": cooldown,
            "maxSeconds": maxSeconds
        };
        sendData.push(systemData);

        // Get triggers
        sendData[1] = [];
        for (id of triggerIds[systemid]) {
            // Get type of trigger
            let newTrigger = $(`#triggerCard${id}`).attr("data-new-trigger");
            let eventTrigger = $(`#changeTrigger${id}`).val();
            let triggerValue1;
            let triggerValue2;
            let triggerRange;
            let seconds = $(`#waterSeconds${id}`).val();


            // Find out type of trigger and get data accordingly
            if (eventTrigger == "time") {
                triggerValue1 = $(`#triggerSecondValue${id}`).val();
            } else if (eventTrigger == "temperature" || eventTrigger == "humidity") {
                triggerRange = $(`#triggerSecondValue${id}`).val();
                triggerValue1 = $(`#triggerThirdValue${id}`).val();
            }            


            let triggerData = {
                "id"           : id,
                "eventTrigger" : eventTrigger,
                "triggerValue1": triggerValue1,
                "triggerValue2": triggerValue2,
                "triggerRange" : triggerRange,
                "seconds"      : seconds,
                "newTrigger"   : newTrigger
            }
            sendData[1].push(triggerData);
        }

        // Send data to PHP script
        $.ajax({
            url: "../files/ajax/saveSystem.php",
            type: 'post',
            data: {"0": JSON.stringify(sendData)},
            success: function(response) {
                location.reload();
            }
        });

        return false;
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
                <input id="triggerSecondValue${triggerId}" type="time" class="form-control" required>
            `);
            $(`#triggerThirdInput${triggerId}`).append("Uhr");

            // Focus on time input
            $(`#triggerSecondValue${triggerId}`).focus();
        } else if (inputValue == "temperature" || inputValue == "humidity") {
            // Change grid to fit content
            $(`#trigger${triggerId}`).css("grid-template-columns", triggerTemperature1_grid);

            // Insert new select
            $(`#triggerSecondInput${triggerId}`).append(`
                <select id="triggerSecondValue${triggerId}" onchange="changeTrigger('${triggerId}', 2);" class="form-select" required>
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
                <input id="triggerThirdValue${triggerId}" type="number" class="form-control" min="${minValue}" max="${maxValue}" required>
            `);
            $(`#unit1_${triggerId}`).append(unit)
            $(`#triggerThirdValue${triggerId}`).focus();
        } else if (inputValue == "between") {
            // TODO
        }
    }
}


function addTrigger(id, systemid) {
    let newTrigger = false;

    // Check if id exists, if not generate new one
    if (id == "") {
        id = `new${++newCounter}`;
        newTrigger = true;
    }

    // Insert trigger into trigger array
    triggerIds[systemid].push(id);

    $(`#addTriggers${systemid}`).append(`
        <div id="triggerCard${id}" class="card mb-3" data-new-trigger="${newTrigger}">
            <div class="card-body trigger-body">
                <div id="trigger${id}" class="trigger-card">
                    <b>Wenn</b>
                    <select id="changeTrigger${id}" onchange="changeTrigger('${id}', 1);" class="form-select" required>
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
                    gieße für <input id="waterSeconds${id}" type="number" class="form-control" min="1" required> Sekunden
                </div>

                <button type="button" onclick="removeTrigger('${id}', '${systemid}')" class="btn btn-outline-danger btn-sm">Entfernen</button>
            </div>
        </div>
    `);
}


function removeTrigger(id, systemid) {
    $(`#triggerCard${id}`).remove();

    // Remove trigger from id
    triggerIds[systemid].splice($.inArray(id, triggerIds[systemid]), 1);
}



function create_db_triggers(triggerData) {
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
