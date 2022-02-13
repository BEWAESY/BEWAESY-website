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
    $(".systemForm").submit(function() {
        let systemid = $(this).attr("id");

        // organize data
        let sendData = [];
        let systemData = {"id": systemid};
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





// Settings Modal
$("#settingsModal").on("show.bs.modal", function(event) {
    let button = event.relatedTarget;

    // Get required values
    let systemId = $(button).attr("data-bs-systemId");
    let systemName = $(button).attr("data-bs-systemName");
    let cooldown = $(button).attr("data-bs-cooldown");
    let maxSeconds = $(button).attr("data-bs-maxSeconds");

    // Insert proper values into modal
    $("#saveSettingsForm").attr("data-bs-systemId", systemId);
    $("#settingsModalLabelName").empty().text(systemName);
    $("#apiKeyPasswordModalTriggerButton").attr("data-bs-systemId", systemId).attr("data-bs-systemName", systemName);
    $("#settingsDeleteModal").attr("data-bs-systemId", systemId).attr("data-bs-systemName", systemName);

    $("#settingsInputName").val(systemName);
    $("#settingsInputCooldown").val(cooldown);
    $("#settingsInputMaxSeconds").val(maxSeconds);
});

// Save settings modal
$(document).ready(function() {
    $("#saveSettingsForm").submit(function() {
        // Show user that the thing is loading and saving
        $("#settingsSubmitButton").prop("disabled", true).prepend('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ');


        let systemId = $("#saveSettingsForm").attr("data-bs-systemId");

        // Get needed values
        let name = $("#settingsInputName").val();
        let cooldown = $("#settingsInputCooldown").val();
        let maxSeconds = $("#settingsInputMaxSeconds").val();

        // Organize data
        let sendData = {
            "systemId":   systemId,
            "name":       name,
            "cooldown":   cooldown,
            "maxSeconds": maxSeconds
        }

        // Send data to PHP script
        $.ajax({
            url: "../files/ajax/saveSystemSettings.php",
            type: "post",
            data: sendData,
            success: function(response) {
                //alert(response);

                if (response == "Success") {
                    bootstrap.Modal.getInstance($("#settingsModal")).hide();

                    let button = $(`#settingsButton${systemId}`);

                    // Update data on page
                    $(button).attr("data-bs-systemName", name);
                    $(button).attr("data-bs-cooldown", cooldown);
                    $(button).attr("data-bs-maxSeconds", maxSeconds);

                    $(`#systemAccordion${systemId}`).empty().text(name);
                } else {
                    alert("Something went wrong");

                    $("#settingsSubmitButton").prop("disabled", false).find("span").remove();
                    $("#settingsModal").unbind("hidden.bs.modal.saveEvent");
                }

                // Reset "save" button when modal is closed
                $("#settingsModal").on("hidden.bs.modal.saveEvent", function() {
                    $("#settingsSubmitButton").prop("disabled", false).find("span").remove();
                    $(this).unbind("hidden.bs.modal.saveEvent");
                });
            }
        })
        
        return(false);
    })
});



// API-Key Modal
// API-Key password Modal
$("#apiKeyPasswordModal").on("show.bs.modal", function(event) {
    let button = event.relatedTarget;

    // Get required values
    let systemId = $(button).attr("data-bs-systemId");
    let systemName = $(button).attr("data-bs-systemName");

    // Insert proper values into modal
    $("#apiKeyPasswordModalForm").attr("data-bs-systemId", systemId);
    $("#apiKeyPasswordModalLabelName").text(systemName);
    $("#apiKeyPasswordEmail").text(userEmail);
});
// Focus on password input field when modal has finished animation
$("#apiKeyPasswordModal").on("shown.bs.modal", function() {
    $("#apiKeyPassword").focus();
});
// Remove password from input when modal is closed
$("#apiKeyPasswordModal").on("hidden.bs.modal", function() {
    $("#apiKeyPassword").val("").removeClass("is-invalid");
});
// Remove "is-invalid" class from password input when input is modified
$("#apiKeyPassword").on("input", function() {
    $(this).removeClass("is-invalid");
})

// Remove API-key from modal when that is closed
$("#apiKeyDataModal").on("hidden.bs.modal", function() {
    $("#apiKeyDataModalApiKeyPlaceholder").text("");
});

// Submit API-Key password Modal
$(document).ready(function() {
    $("#apiKeyPasswordModalForm").submit(function() {
        // Deactivate input and submit button
        $("#apiKeyPassword").attr("disabled", true);
        $("#apiKeyPasswordModalSubmitButton").attr("disabled", true);
        $("#apiKeyPassword").removeClass("is-invalid");

        let systemId = $("#apiKeyPasswordModalForm").attr("data-bs-systemId");

        // Send data to PHP script
        $.ajax({
            url: "../files/ajax/getApiKey.php",
            type: "post",
            data: {"systemId": systemId, "password": $("#apiKeyPassword").val()},
            success: function(response) {
                try {
                    response = JSON.parse(response);
                } catch {}

                if (response[0] == "Success") {
                    // Hide this modal
                    bootstrap.Modal.getInstance($("#apiKeyPasswordModal")).hide();

                    // Show the modal with data
                    new bootstrap.Modal($("#apiKeyDataModal")).show();

                    // Insert data
                    $("#apiKeyDataModalLabelName").text(response[1]);
                    $("#apiKeyDataModalIdPlaceholder").text(systemId);
                    $("#apiKeyDataModalApiKeyPlaceholder").text(response[2]);
                } else if (response == "wrongPassword") {
                    $("#apiKeyPassword").addClass("is-invalid");
                } else {
                    alert("Something went wrong");
                }

                $("#apiKeyPassword").attr("disabled", false);
                $("#apiKeyPasswordModalSubmitButton").attr("disabled", false);
            }
        })
        
        return(false);
    })
});




// Delete System Modal
$("#deleteSystemModal").on("show.bs.modal", function(event) {
    let button = event.relatedTarget;

    // Hide loading animation if it is shown from a previous deletion
    $("#deleteSystemSubmitButton").find("span").remove();

    // Get required values
    let systemId = $(button).attr("data-bs-systemId");
    let systemName = $(button).attr("data-bs-systemName");

    // Insert proper values into modal
    $("#deleteSystemForm").attr("data-bs-systemId", systemId);
    $("#deleteSystemModalLabelName").text(systemName);
    $("#deleteSystemBodyTextName").text(systemName);
    $("#deleteSystemNameInput").attr("placeholder", systemName).attr("data-bs-systemName", systemName).val("");
    $("#deleteSystemSubmitButton").attr("disabled", true);
});
// Focus on delete system input field when modal has finished animation
$("#deleteSystemModal").on("shown.bs.modal", function() {
    $("#deleteSystemNameInput").focus();
})

// Delete System check input
$("#deleteSystemNameInput").on("input", function() {
    // Get required values
    let systemName = $(this).attr("data-bs-systemName").toLowerCase();
    let inputValue = $(this).val().toLowerCase();

    if (systemName == inputValue) {
        $("#deleteSystemSubmitButton").attr("disabled", false);
    } else {
        $("#deleteSystemSubmitButton").attr("disabled", true);
    }
});

// Submit delete system Modal
$(document).ready(function() {
    $("#deleteSystemForm").submit(function() {
        // Show user that the thing is loading and deleting the system
        $("#deleteSystemSubmitButton").attr("disabled", true).prepend('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ');

        let systemId = $("#deleteSystemForm").attr("data-bs-systemId");

        // Send data to PHP script
        $.ajax({
            url: "../files/ajax/deleteSystem.php",
            type: "post",
            data: {"systemId": systemId},
            success: function(response) {
                if (response == "Success") {
                    // Close Modal
                    bootstrap.Modal.getInstance($("#deleteSystemModal")).hide();

                    // Remove system from page
                    $(`#accordion${systemId}`).remove();
                } else {
                    // Give an error message when something went wrong and enable form submission again
                    alert("Something went wrong");
                    $("#deleteSystemSubmitButton").prop("disabled", false).find("span").remove();
                }
            }
        })
        
        return(false);  // Don't reload the page after form submission
    })
});
