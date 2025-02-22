<?php include "includes/header_admin.php"; ?>

<div class="main-container">
    <div class="pd-ltr-20">
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">Recent Bookings</h4>
            </div>
            <div class="pb-20">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered hover multiple-select-row data-table-export">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>SR NO</th>
                                <th>Franchise Name</th>
                                <th>Lab Name</th>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Order Amount</th>
                                <th>Test Name</th>
                                <th>Booking Date & Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php recentBookings(); ?>
                        </tbody>
                    </table>
                </div>
                <!-- Buttons Below the Table, Left-Aligned -->
                <div class="d-flex justify-content-start mt-3">
                    <button type="button" class="btn btn-info mx-1" onclick="updateStatus('In-Process')">In-Process</button>
                    <button type="button" class="btn btn-success mx-1" onclick="updateStatus('Completed')">Completed</button>
                    <button type="button" class="btn btn-danger mx-1" onclick="updateStatus('Rejected/Cancelled')">Rejected/Cancelled</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // $(document).ready(function() {
    //     // Select/Deselect All
    //     $("#selectAll").click(function() {
    //         $("input[name='booking_ids[]']").prop('checked', this.checked);
    //     });
    // });



    $(document).ready(function() {
        // Select/Deselect All Checkboxes
        $("#selectAll").on("change", function() {
            $(".booking-checkbox").prop("checked", $(this).prop("checked"));
        });

        // Ensure "Select All" only checks when all checkboxes are selected
        $(document).on("change", ".booking-checkbox", function() {
            let totalCheckboxes = $(".booking-checkbox").length;
            let checkedCheckboxes = $(".booking-checkbox:checked").length;

            $("#selectAll").prop("checked", totalCheckboxes === checkedCheckboxes);
        });
    });





    // function updateStatus(status) {
    //     let selectedIds = [];

    //     $("input.booking-checkbox:checked").each(function() {
    //         selectedIds.push($(this).val());
    //     });

    //     if (selectedIds.length === 0) {
    //         alert("Please select at least one booking.");
    //         return;
    //     }

    //     $.ajax({
    //         url: "update_booking_status.php",
    //         type: "POST",
    //         contentType: "application/json", // Ensure JSON format
    //         data: JSON.stringify({
    //             booking_ids: selectedIds,
    //             status: status
    //         }),
    //         success: function(response) {
    //             console.log("Raw Response:", response);

    //             // If response is an object, convert it to a string
    //             let responseText = typeof response === "string" ? response : JSON.stringify(response);

    //             console.log("Processed Response:", responseText);

    //             alert(responseText);

    //             if (responseText.includes("successfully")) {
    //                 location.reload();
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("AJAX Error: ", error);
    //             alert("Error updating status.");
    //         }
    //     });
    // }

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
                console.error("Response Text:", xhr.responseText); // Show actual server response
                alert("Request failed. Check console for details.");
            }
        });

    }
</script>

<?php include "includes/footer_admin.php"; ?>