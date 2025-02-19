document.addEventListener("DOMContentLoaded", function() {
    // Get all checkboxes
    const checkboxes = document.querySelectorAll("input[name='selectedTests[]']");
    const selectedTestsContainer = document.querySelector(".form-group p"); // Step-2: Selected Tests

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function() {
            updateSelectedTests();
        });
    });

    function updateSelectedTests() {
        let selectedTests = [];
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedTests.push(checkbox.value);
            }
        });

        // Update the display text
        selectedTestsContainer.textContent = selectedTests.length > 0 ? selectedTests.join(", ") : "No tests selected";
    }
});