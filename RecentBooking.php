<?php include "includes/header.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100vw;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-top: 15vh;


        }

        .search,
        .middle {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
            width: 100%;
        }

        .search input,
        .middle input,
        .middle select {
            flex: 1;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            /* Adjusting padding to make buttons reasonably sized */
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .bottom {
            display: flex;
            flex-direction: row;
            gap: 10px;
            overflow-x: scroll;
        }

        /* Custom Scrollbar */
        .bottom::-webkit-scrollbar {
            width: 10px;
        }

        .bottom::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .bottom::-webkit-scrollbar-thumb {
            background: #007bff;
            border-radius: 10px;
        }

        .bottom::-webkit-scrollbar-thumb:hover {
            background: #0056b3;
        }


        @media (min-width:1301px) {
            .container {
                width: 80vw;
                margin-left: auto;
                margin-right: 0px;
            }

        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Recent Booking</h1>
        <div class="search">
            <input type="text" placeholder="Search">
            <button>Search</button>
        </div>
        <div class="middle">
            <input type="date">
            <select name="" id="">
                <option value="select an Option">Select an Option</option>
                <option value="Home Collection">Home Collection</option>
                <option value="Courier">Courier</option>
                <option value="Pickup">Pickup</option>
                <option value="Sample Drawn">Sample Drawn</option>
            </select>
            <select name="" id="">
                <option value="All Labs">All Labs</option>
            </select>
            <select name="" id="">
                <option value="">Order Status</option>
                <option value="Pending">Pending</option>
                <option value="Process">Process</option>
                <option value="Complete">Complete</option>
                <option value="Resample">Resample</option>
                <option value="Reject">Reject</option>
            </select>
            <button>Clear Filter</button>
            <button>Download Excel</button>
        </div>
        <div class="bottom">
            <span>Select Dispatch Item</span>
            <span>SR No</span>
            <span>Patient ID</span>
            <span>Booking ID</span>
            <span>Patient Name</span>
            <span>Gender</span>
            <span>Patient Age</span>
            <span>Lab Name</span>
            <span>Order Status</span>
            <span>Dispatch Type</span>
            <span>Order Amount</span>
            <span>Booking Date</span>
            <span>Test Name</span>
        </div>
    </div>
</body>

</html>
<?php include "includes/footer.php"; ?>