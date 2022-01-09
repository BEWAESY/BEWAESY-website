// Define basic values
// Define grids for triggers
const triggerBasicGrid = $(".trigger-card").css("grid-template-columns");

// Time grid
const timeGrid = triggerBasicGrid + " 100px";

// Temperature + humidity grid
const triggerTemperature1_grid = triggerBasicGrid + " 117px";  // Add value for second select
const triggerTemparature2_normalGrid = triggerTemperature1_grid + " 90px 16px";  // Grid that is used if values "smaller" or "bigger" are chosen
//const triggerTemperature2_specialGrid;  // Grid that is used if value "between" is chosen



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
            // Chnage grid
            $(`#trigger${triggerId}`).css("grid-template-columns", timeGrid);

            // Insert new time select
            $(`#triggerSecondInput${triggerId}`).append(`
                <input id="triggerSecondValue${triggerId}" type="time" class="form-control">
            `);

            // Focus on time input
            $(`#triggerSecondValue${triggerId}`).focus();
        } else if (inputValue == "temperature" || inputValue == "humidity") {
            // Change grid to fit content
            $(`#trigger${triggerId}`).css("grid-template-columns", triggerTemperature1_grid);

            // Insert new select
            $(`#triggerSecondInput${triggerId}`).append(`
                <select id="triggerSecondValue${triggerId}" onchange="changeTrigger(${triggerId}, 2);" class="form-select" aria-label="Auswählen, ob Aktion ausgeführt werden soll, wenn Wert kleiner, größer oder gleich angegebenem Wert.">
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
