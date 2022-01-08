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
            // TODO
        } else if (inputValue == "temperature" || inputValue == "humidity") {
            $(`#triggerSecondInput${triggerId}`).append(`
                <select id="triggerSecondValue${triggerId}" onchange="changeTrigger(${triggerId}, 2);" class="form-select" aria-label="Auswählen, ob Aktion ausgeführt werden soll, wenn Wert kleiner, größer oder gleich angegebenem Wert.">
                    <option selected></option>
                    <option value="smaller">kleiner</option>
                    <option value="bigger">größer</option>
                    <option value="equal" disabled>zwischen</option>
                </select>`
            );
            $(`#triggerSecondValue${triggerId}`).focus();
        }
    } else if (valuePlace == 2) {
        let inputValue = $(`#triggerSecondValue${triggerId}`).val();
        let unit = ($(`#changeTrigger${triggerId}`).val() == "temperature") ? "°C" : "%";

        // Remove old input
        $(`#triggerThirdInput${triggerId}`).empty();
        $(`#unit1_${triggerId}`).empty();
        
        // Check value
        if (inputValue == "smaller" || inputValue == "bigger") {
            $(`#triggerThirdInput${triggerId}`).append(`
                <input id="triggerThirdValue${triggerId}" type="number" class="form-control">
            `);
            $(`#unit1_${triggerId}`).append(unit)
            $(`#triggerThirdValue${triggerId}`).focus();
        } else if (inputValue == "between") {
            // TODO
        }
    }
}
