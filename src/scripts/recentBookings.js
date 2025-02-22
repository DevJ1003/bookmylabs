// <!-- ========================================== rejection modal =============================================== -->
function openRejectionModal(bookingId) {
    console.log("Opening modal for ID:", bookingId); // Debugging log
    let modal = document.getElementById("rejectionModal");

    if (!modal) {
        console.error("Modal not found in the document!");
        return;
    }

    document.getElementById("bookingId").value = bookingId;
    modal.style.display = "flex"; // Ensure modal is visible
}

function closeRejectionModal() {
    let modal = document.getElementById("rejectionModal");
    modal.style.display = "none";
}

// modal submission function
function submitRejection() {
    let bookingId = document.getElementById("bookingId").value;
    let rejectionReason = document.getElementById("rejectionReason").value.trim();

    if (rejectionReason === "") {
        alert("Please enter a rejection reason.");
        return;
    }

    let formData = new FormData();
    formData.append("id", bookingId);
    formData.append("rejection_reason", rejectionReason);

    // Log the data before sending
    console.log("Submitting data:", {
        id: bookingId,
        rejection_reason: rejectionReason
    });

    fetch("bookingRejected", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log("Server response:", data); // Log server response

            if (data.trim() === "success") {
                alert("Booking Rejected Successfully!");
                closeRejectionModal();
                location.reload();
            } else {
                alert("Error rejecting booking: " + data);
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            alert("Something went wrong. Check console.");
        });
}
// <!-- ========================================================================================================== -->
// <!-- ======================================== filters, search bar js ========================================== -->
$(document).ready(function() {
    $("#selectAll").on("change", function() {
        $(".booking-checkbox").prop("checked", $(this).prop("checked"));
    });

    $(document).on("change", ".booking-checkbox", function() {
        let totalCheckboxes = $(".booking-checkbox").length;
        let checkedCheckboxes = $(".booking-checkbox:checked").length;

        $("#selectAll").prop("checked", totalCheckboxes === checkedCheckboxes);
    });
});

function updateStatus(status) {
    let selectedIds = [];

    $("input.booking-checkbox:checked").each(function() {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
        alert("Please select at least one booking.");
        return;
    }

    $.ajax({
        url: "update_booking_status",
        type: "POST",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify({
            booking_ids: selectedIds,
            status: status
        }),
        success: function(response) {
            console.log("Raw Response:", response);
            if (response.success) {
                alert(response.message);
                location.reload();
            } else {
                alert("Error: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            console.error("Response Text:", xhr.responseText);
            alert("Request failed. Check console for details.");
        }
    });

}


document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");
    const orderStatusFilter = document.getElementById("orderStatusFilter");
    const dispatchOptionFilter = document.getElementById("dispatchOptionFilter");
    const labNameFilter = document.getElementById("labNameFilter");
    const dateFilter = document.getElementById("dateFilter");
    const clearFilterButton = document.getElementById("clearFilterButton"); // Clear Filter Button
    const resultsTable = document.querySelector(".data-table-export-recent-booking tbody");

    function fetchFilteredResults() {
        const query = searchInput.value.trim();
        const status = orderStatusFilter.value;
        const dispatchOption = dispatchOptionFilter.value;
        const labName = labNameFilter.value;
        const selectedDate = dateFilter.value;

        fetch(`../search_recent_booking.php?query=${encodeURIComponent(query)}&status=${encodeURIComponent(status)}&dispatch_option=${encodeURIComponent(dispatchOption)}&lab_name=${encodeURIComponent(labName)}&date=${encodeURIComponent(selectedDate)}`)
            .then(response => response.text())
            .then(data => {
                resultsTable.innerHTML = data;
            })
            .catch(error => console.error("Error fetching filtered results:", error));
    }

    searchButton.addEventListener("click", function() {
        fetchFilteredResults();
    });

    searchInput.addEventListener("keyup", function(event) {
        if (event.key === "Enter") {
            fetchFilteredResults();
        }
    });

    orderStatusFilter.addEventListener("change", function() {
        fetchFilteredResults();
    });

    dispatchOptionFilter.addEventListener("change", function() {
        fetchFilteredResults();
    });

    labNameFilter.addEventListener("change", function() {
        fetchFilteredResults();
    });

    dateFilter.addEventListener("change", function() {
        fetchFilteredResults();
    });

    // ✅ Clear Filter Function
    clearFilterButton.addEventListener("click", function() {
        searchInput.value = ""; // Clear search input
        orderStatusFilter.value = ""; // Reset status filter
        dispatchOptionFilter.value = ""; // Reset dispatch option
        labNameFilter.value = ""; // Reset lab filter
        dateFilter.value = ""; // Reset date filter

        fetchFilteredResults(); // Reload data without filters
    });
});

document.getElementById("downloadExcel").addEventListener("click", function() {
    const query = encodeURIComponent(document.getElementById("searchInput").value.trim());
    const status = encodeURIComponent(document.getElementById("orderStatusFilter").value);
    const dispatchOption = encodeURIComponent(document.getElementById("dispatchOptionFilter").value);
    const labName = encodeURIComponent(document.getElementById("labNameFilter").value);
    const selectedDate = encodeURIComponent(document.getElementById("dateFilter").value);

    // Redirect to export_to_excel.php with filters as query parameters
    window.location.href = `../export_to_excel.php?query=${query}&status=${status}&dispatch_option=${dispatchOption}&lab_name=${labName}&date=${selectedDate}`;
});
// <!-- ========================================================================================================== -->