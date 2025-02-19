<?php include "includes/header.php"; ?>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Wallet Recharge Request</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Recharge Request</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Recharge Requests</h4>
                </div>
                <div class="pb-20">
                    <div class="table-responsive"> <!-- Responsive table wrapper -->
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>UI Reference No</th>
                                    <th>Amount</th>
                                    <th>Proof of Payment</th>
                                    <th>Lab Name</th>
                                    <th>Status</th>
                                    <th>Actions</th> <!-- Added Actions column for buttons -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Pagination setup
                                $limit = 5; // Number of entries per page
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $offset = ($page - 1) * $limit;

                                // Sample data - replace this with your actual database query
                                $requests = [
                                    [
                                        'reference_no' => 'REQ123',
                                        'amount' => '100.00',
                                        'proof' => 'proof1.jpg',
                                        'lab_name' => 'Lab A',
                                        'status' => 'Pending'
                                    ],
                                    [
                                        'reference_no' => 'REQ124',
                                        'amount' => '150.00',
                                        'proof' => 'proof2.jpg',
                                        'lab_name' => 'Lab B',
                                        'status' => 'Approved'
                                    ],
                                    // Add more requests as needed
                                ];

                                $totalEntries = count($requests); // Use the count of your actual entries
                                $totalPages = ceil($totalEntries / $limit);

                                // Fetch entries for the current page
                                $pagedRequests = array_slice($requests, $offset, $limit);

                                foreach ($pagedRequests as $request) {
                                    echo "<tr>";
                                    echo "<td>{$request['reference_no']}</td>";
                                    echo "<td>{$request['amount']}</td>";
                                    echo "<td><img src='{$request['proof']}' alt='Proof of Payment' style='width: 100px; height: auto;'></td>";
                                    echo "<td>{$request['lab_name']}</td>";
                                    echo "<td>{$request['status']}</td>";
                                    echo "<td class='text-right'>
                                            <button class='btn-sm btn-success' onclick='updateStatus(\"{$request['reference_no']}\", \"Approved\")'>Approve</button>
                                            <button class='btn-sm btn-danger' onclick='updateStatus(\"{$request['reference_no']}\", \"Rejected\")'>Reject</button>
                                          </td>"; // Buttons in the same row
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="pagination">
                        <ul class="pagination">
                            <?php if($page > 1): ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a></li>
                            <?php endif; ?>

                            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if($page < $totalPages): ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(referenceNo, status) {
    // Implement the logic to update the status in the database
    // For example, make an AJAX request to update the status
    alert('Status for ' + referenceNo + ' updated to ' + status);
}
</script>

<?php include "includes/footer.php"; ?>

<style>
.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center; /* Center the pagination */
}
.pagination .page-item {
    margin: 0 5px; /* Space between pagination items */
}
.table-responsive {
    overflow-x: auto; /* Allow horizontal scroll on smaller screens */
}
@media (max-width: 768px) {
    .table th, .table td {
        padding: 0.5rem; /* Reduce padding for smaller screens */
    }
    .table img {
        max-width: 100px; /* Limit image size */
    }
}
</style>