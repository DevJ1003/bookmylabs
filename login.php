<?php include "includes/functions.php";

// Check if the user is already logged in
if (isset($_SESSION['id'])) {
	// If logged in, redirect to the index page
	header('Location: index.php');
	exit();
}

?>

<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Book My Labs</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
	<link rel="stylesheet" type="text/css" href="src/styles/login.css">
</head>

<body class="login-page">
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<img class="logo" src="vendors/images/BOOK-MY-LAB.png" alt="">
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-10">
					<div class="login-container">
						<img src="https://static.vecteezy.com/system/resources/previews/047/025/200/non_2x/illustration-of-an-anamnesis-system-providing-information-about-diseases-and-healthcare-databases-in-a-flat-style-cartoon-background-vector.jpg" alt="Login Illustration">
						<div class="login-box">
							<div class="login-title">
								<h2 class="text-center text-primary">Login</h2>
							</div>
							<form action="login" id="loginForm" method="POST" class="form">
								<?php loginUser();
								displayMessage();
								?>

								<!-- CSRF Token -->
								<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

								<div class="input-group custom">
									<input type="text" name="email" id="email" class="form-control form-control-lg" placeholder="Email" required data-validation-required-message="Please enter email">
									<div class="input-group-append custom">
										<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
									</div>
								</div>
								<div class="input-group custom">
									<input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Password" required data-validation-required-message="Please enter password">
									<div class="input-group-append custom">
										<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
									</div>
								</div>
								<div class="row pb-30">
									<div class="col-6">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="customCheck1" name="remember">
											<label class="custom-control-label" for="customCheck1">Remember</label>
										</div>
									</div>
									<div class="col-6">
										<div class="forgot-password"><a href="forgot.php">Forgot Password</a></div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="input-group mb-0">
											<input class="btn btn-primary btn-lg btn-block" type="submit" name="login_form" value="Sign In">
										</div>
										<div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373"></div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- js -->
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>

	<script>
		setTimeout(function() {
			var alertBox = document.getElementById('autoHideAlert');
			if (alertBox) {
				alertBox.style.transition = "opacity 0.5s ease-out";
				alertBox.style.opacity = "0";
				setTimeout(function() {
					alertBox.style.display = "none";
				}, 500);
			}
		}, 3000); // 3 seconds before disappearing
	</script>

</body>

</html>