<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 150px;
            margin-bottom: 15px;
        }

        .message {
            font-size: 18px;
            color: green;
            font-weight: bold;
        }
    </style>
    <script>
        setTimeout(() => {
            window.close();
        }, 5000);
    </script>
</head>

<body>
    <div class="container">
        <img src="../vendors/images/BOOK-MY-LAB.jpg" alt="Lab Logo" class="logo">
        <p class="message">✅ Your test details have been successfully submitted!</p>
        <p>This page will close automatically in 5 seconds...</p>
    </div>
</body>

</html>