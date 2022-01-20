$(document).ready(function() {
    $("form").submit(function() {
        // Disable input
        $("#nameInput, #formSubmit").prop("disabled", true);


        // Get needed data
        let name = $("#nameInput").val();


        // Send data to PHP to create a new system
        $.ajax({
            url: "../files/ajax/add-system.php",
            type: "post",
            data: {"name": name},
            success: function(response) {
                generateResponse(response);
            }
        });
        return false;
    })
})


function generateResponse(rawResponse) {
    response = JSON.parse(rawResponse);

    // Get needed values
    let id = response[0];
    let name = response[1];
    let apiKey = response[2];

    // Display feedback to user
    $("#response").empty().append(`
        <div class="alert alert-success mt-4 mb-0" role="alert">
            <div class="d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <div>
                    <h4 class="alert-heading">System erstellt</h4>
                </div>
            </div>

            <p class="mt-2">Dein Bewässerungssystem wurde erfolgreich hinzugefügt. Bitte füge die folgenden Daten auf deinem Bewässerungssystem hinzu, damit es mit unserer API kommunizieren kann. Wie das geht kannst du <a href="../help/add-system">hier</a> einsehen:</p>
            <hr>
            <p class="mb-0">ID: ${id}</p>
            <p class="mb-0">Name: ${name}</p>
            <p>API-Key: ${apiKey}</p>
        </div>
    `);
}
