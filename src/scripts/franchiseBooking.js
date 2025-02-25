document.addEventListener("DOMContentLoaded", function() {
    console.log("franchiseBooking.js loaded"); // Debugging log

    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");
    const orderStatusFilter = document.getElementById("orderStatusFilter");
    const dispatchOptionFilter = document.getElementById("dispatchOptionFilter");
    const labNameFilter = document.getElementById("labNameFilter"); // Ensure it's present
    const dateFilter = document.getElementById("dateFilter");
    const clearFilterButton = document.getElementById("clearFilterButton");
    const resultsTable = document.querySelector(".data-table-export-recent-booking tbody");

    function fetchFilteredResults() {
        const query = searchInput.value.trim();
        const status = orderStatusFilter.value;
        const dispatchOption = dispatchOptionFilter.value;
        const labName = labNameFilter ? labNameFilter.value : ""; // Prevent errors
        const selectedDate = dateFilter.value;

        console.log("Fetching results with:", { query, status, dispatchOption, labName, selectedDate });

        fetch(`search_recent_booking_admin.php?query=${encodeURIComponent(query)}&status=${encodeURIComponent(status)}&dispatch_option=${encodeURIComponent(dispatchOption)}&lab_name=${encodeURIComponent(labName)}&date=${encodeURIComponent(selectedDate)}`)
            .then(response => response.text())
            .then(data => {
                console.log("Server Response:", data); // ✅ Log Response
                resultsTable.innerHTML = data;
            })
            .catch(error => console.error("Error fetching filtered results:", error));
    }

    // ✅ Search Functionality
    searchButton.addEventListener("click", function() {
        fetchFilteredResults();
    });

    searchInput.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            fetchFilteredResults();
        }
    });

    // ✅ Filters
    orderStatusFilter.addEventListener("change", fetchFilteredResults);
    dispatchOptionFilter.addEventListener("change", fetchFilteredResults);
    
    if (labNameFilter) {
        labNameFilter.addEventListener("change", fetchFilteredResults);
    }

    dateFilter.addEventListener("change", fetchFilteredResults);

    // ✅ Clear Filters
    clearFilterButton.addEventListener("click", function() {
        searchInput.value = "";
        orderStatusFilter.value = "";
        dispatchOptionFilter.value = "";
        if (labNameFilter) labNameFilter.value = "";
        dateFilter.value = "";

        fetchFilteredResults();
    });
});

// <!-- ======================================== Download Excel ============================================== -->
document.getElementById("downloadExcel").addEventListener("click", function() {
    const query = encodeURIComponent(document.getElementById("searchInput").value.trim());
    const status = encodeURIComponent(document.getElementById("orderStatusFilter").value);
    const dispatchOption = encodeURIComponent(document.getElementById("dispatchOptionFilter").value);
    const labName = encodeURIComponent(document.getElementById("labNameFilter") ? document.getElementById("labNameFilter").value : "");
    const selectedDate = encodeURIComponent(document.getElementById("dateFilter").value);

    window.location.href = `../export_to_excel.php?query=${query}&status=${status}&dispatch_option=${dispatchOption}&lab_name=${labName}&date=${selectedDate}`;
});
