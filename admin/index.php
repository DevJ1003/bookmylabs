<?php include "includes/header_admin.php"; ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="card-box pd-20 height-100-p mb-30">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/health-checkup-illustration-download-in-svg-png-gif-file-formats--doctor-visiting-patient-visit-by-checking-report-healthcare-and-medical-pack-illustrations-4695022.png?f=webp" alt="">
                    </div>
                    <div class="col-md-8">
                        <h4 class="font-20 weight-500 mb-10 text-capitalize">
                            Welcome back, <div class="weight-600 font-30 text-blue">Admin</div>
                        </h4>
                        <p class="font-18 max-width-600">
                            Our Platform allows doctors appointments and diagnostic lab test booking services across India through rich network of healthcare service providers.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="mb-30 mt-30 text-blue h4">Dashboard</h4>
        <div class="row">
            <div class="col-md-4 mb-30">
                <div class="card-box pricing-card-style2">
                    <div class="pricing-card-header">
                        <div class="left">
                            <h5>Total Labs</h5>
                        </div>
                        <div class="right">
                            <div class="pricing-price">
                                <?php totalLabs(); ?><span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-30">
                <div class="card-box pricing-card-style2">
                    <div class="pricing-card-header">
                        <div class="left">
                            <h5>Total Franchise</h5>
                        </div>
                        <div class="right">
                            <div class="pricing-price">
                                <?php totalFranchise(); ?><span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-30">
                <div class="card-box pricing-card-style2">
                    <div class="pricing-card-header">
                        <div class="left">
                            <h5>Total Test Bookings</h5>
                        </div>
                        <div class="right">
                            <div class="pricing-price">
                                <?php totalBookings(); ?><span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-30">
                <div class="card-box pricing-card-style2">
                    <div class="pricing-card-header">
                        <div class="left">
                            <h5>Total </h5>
                        </div>
                        <div class="right">
                            <div class="pricing-price">
                                10000<span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-30">
                <div class="card-box pricing-card-style2">
                    <div class="pricing-card-header">
                        <div class="left">
                            <h5>Net Partner</h5>
                        </div>
                        <div class="right">
                            <div class="pricing-price">
                                21<span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-30">
                <div class="card-box pricing-card-style2">
                    <div class="pricing-card-header">
                        <div class="left">
                            <h5>Total Completed</h5>
                        </div>
                        <div class="right">
                            <div class="pricing-price">
                                21<span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-4 mb-30">
				<div class="card-box pricing-card-style2">
					<div class="pricing-card-header">
						<div class="left">
							<h5>Standard</h5>
						</div>
						<div class="right">
							<div class="pricing-price">
								€10<span>/month</span>
							</div>
						</div>
					</div>
				</div>
			</div> -->
        </div>
    </div>

    <?php include "includes/footer_admin.php" ?>