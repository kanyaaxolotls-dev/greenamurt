<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap:">

	<link rel="icon" href="images/favicon.png">
	<title>SDV SHOPS PVT LTD</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,500i,700,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/framework7.bundle.css">
	<link rel="stylesheet" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/style.css">
 

</head>
<body>
	
	<div id="app">

		<div class="view view-main view-init ios-edges" data-url="/">
			<div class="page page-home page-with-subnavbar">
				<div class="toolbar tabbar tabbar-labels toolbar-bottom">
					<div class="toolbar-inner">
						<a href="#tab-home" class="tab-link tab-link-active">
							<i class="fas fa-home"></i>
							<span class="tabbar-label">Home</span>
						</a>
						<a href="#tab-search" class="tab-link">
							<i class="fas fa-search"></i>
							<span class="tabbar-label">Search</span>
						</a>
						<a href="#tab-brand" class="tab-link">
							<i class="fas fa-medal"></i>
							<span class="tabbar-label">Official Brand</span>
						</a>
						<a href="#tab-cart" class="tab-link">
							<i class="fas fa-shopping-cart"></i>
							<span class="tabbar-label">Cart</span>
						</a>
						<a href="#tab-account" class="tab-link">
							<i class="fas fa-user"></i>
							<span class="tabbar-label">Account</span>
						</a>
					</div>
				</div>
				<div class="tabs">
					<div id="tab-home" class="tab tab-active tab-home">
						<!-- home -->

						<!-- navbar home -->
						<div class="navbar navbar-home">
							<div class="navbar-inner">
								<div class="left">
									<a href="#" class="panel-open" data-panel="left">
										<i class="fas fa-bars"></i>
									</a>
								</div>
								<div class="title">
									<img src="images/casho.jpeg">
								</div>
								<div class="right">
									<a href="/wishlist/">
										<i class="fas fa-heart"></i>
									</a>
									<a href="/notification/">
										<i class="fas fa-bell"></i>
										<span></span>
									</a>
								</div>
							</div>
						</div>
						<div class="subnavbar">
							<form class="searchbar searchbar-init" data-search-container=".search-list" data-search-in=".item-title">
								<div class="searchbar-inner">
									<div class="searchbar-input-wrap">
										<input type="search" placeholder="Search for products for stores">
										<i class="searchbar-icon"></i>
										<span class="input-clear-button"></span>
									</div>
									<span class="searchbar-disable-button">Cancel</span>
								</div>
							</form>
						</div>
						<!-- end navbar home -->

						<!-- sidebarbar or panel -->
						<div class="panel-backdrop"></div>
						<div class="panel panel-left panel-cover sidebar">
							<div class="list accordion-list">
								<ul>
									<li class="accordion-item">
										 <div class="panel panel-left panel-reveal panel-resizable panel-init">
									    MY ACCOUNT
									    </div>
									</li>
									<!-- <li class="accordion-item">
										<a href="#" class="item-link item-content">
											<div class="item-media">
												<i class="fas fa-tshirt"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Shop Pages</div>
											</div>
										</a>
										<div class="accordion-item-content">

											
											<div class="divider-space-text"></div>
											
											<a href="/shopping-cart/" class="panel-close"><i class="fas fa-shopping-cart"></i>Shopping Cart</a>
											<a href="/all-categories/" class="panel-close"><i class="fas fa-layer-group"></i>Categories</a>
											<a href="/wishlist/" class="panel-close"><i class="fas fa-heart"></i>Wishlist</a>
											<a href="/checkout/" class="panel-close"><i class="fas fa-cart-arrow-down"></i>Checkout</a>
											<a href="/account-buyer/" class="panel-close"><i class="fas fa-user"></i>Account Buyer</a>
											<a href="/account-seller/" class="panel-close"><i class="fas fa-user"></i>Account Seller</a>
											<a href="/tracking-order/" class="panel-close"><i class="fas fa-truck"></i>Tracking Order</a>
											<a href="/order-history/" class="no-mb panel-close"><i class="fas fa-history"></i><span>Order History</span></a>

											
											<div class="divider-space-text"></div>
											

										</div>
									</li> -->


						<li>
							<div class="account-buyer segments">
							<div class="container">
								<div class="header-account content-shadow">
									<!-- <img src="images/user-buyer6.png" alt="">
									<div class="title-name">
									
									</div> -->
									<img src="images/casho.jpeg">
								</div>
							</div>
						</div>
						</li>



									<li>
										<a href="/blog/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-code"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">My QR Code</div>
											</div>
										</a>
									</li>
									<li>
										<a href="/blog/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-map-marker-alt"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">My Addresses</div>
											</div>
										</a>
									</li>
									<li>
										<a href="/blog/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-language"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">My Laguage</div>
											</div>
										</a>
									</li>
									<li>
										<a href="/blog/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-key"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Change Password</div>
											</div>
										</a>
									</li>
									<li>
										<a href="/blog/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-lock"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Disale Screen Lock</div>
											</div>
										</a>
									</li>
									<li>
										<a href="/blog/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-user-plus"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Edit Profile</div>
											</div>
										</a>
									</li>
									

									<li>
										<a href="/offer-zone/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-gift"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Offers</div>
											</div>
										</a>
									</li>
								<!-- 	<li>
										<a href="/blog-single/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-rss"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Blog Single</div>
											</div>
										</a>
									</li> -->
									<li>
										<a href="/passbook/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-rss"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">My Passbook</div>
											</div>
										</a>
									</li>
<!-- 
									<li>
										<a href="/sign-in/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-sign-in-alt"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Sign In</div>
											</div>
										</a>
									</li>
									<li>
										<a href="/sign-up/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-user-plus"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Sign Up</div>
											</div>
										</a>
									</li> -->
									<li>
										<a href="/contact-seller/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-envelope"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Contact Seller</div>
											</div>
										</a>
									</li>
									<li>
										<a href="/blog/" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-blog"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Policies</div>
											</div>
										</a>
									</li>
									<li>
										<a href="#" class="item-link item-content panel-close">
											<div class="item-media">
												<i class="fas fa-power-off"></i>
											</div>
											<div class="item-inner">
												<div class="item-title">Logout</div>
											</div>
										</a>
									</li>
								</ul>
							</div>
						</div>
						<!-- end sidebarbar or panel -->

						<!-- slider -->
						<div class="slider">
							<div class="container">
								<div data-pagination='{"el": ".swiper-pagination"}' data-space-between="10" class="swiper-container swiper-init swiper-container-horizontal">
									<div class="swiper-pagination"></div>
									<div class="swiper-wrapper">
										<div class="swiper-slide">
											<div class="content">
												<div class="mask"></div>
												<img src="images/banner11.jpeg" alt="">
											</div>
										</div>
										<div class="swiper-slide">
											<div class="content">
												<div class="mask"></div>
												<img src="images/banner22.jpeg" alt="">
											</div>
										</div>
										<div class="swiper-slide">
											<div class="content">
												<div class="mask"></div>
												<img src="images/banner33.jpeg" alt="">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end slider -->

						<!-- cateogries -->
						<div class="categories segments">
							<div class="container">
								<!-- <div class="categories-details segments">
									<div class="card">
										<div class="card-content card-content-padding">
											<h5> Money Transfer</h5>
										</div>
									</div> 
								</div> -->
								<div class="row"> 
									<div class="col-20">
										<div class="content">
											<a href="/transfer/" class="external">
												<div class="icon">
													<i class="fas fa-exchange-alt"></i>
												</div>
												<span>Transfer</span>
											</a>
										</div>
									</div><!-- 
									<div class="col-20">
										<div class="content">
											<a href="/recharge/"> 
												<div class="icon">
													<i class="fas fa-mobile-alt"></i>
												</div>
												<span>Recharge</span>
											</a>
										</div>
									</div> -->
									<div class="col-20">
										<div class="content">
											<a href="/casho/">
												<div class="icon">
													<i class="fas fa-blog"></i>
												</div>
												<span>CashO Deals</span>
											</a>
										</div>
									</div>

									<div class="col-20">
										<div class="content">
											<a href="/vouchers/">
												<div class="icon">
													<i class="fas fa-envelope"></i>
												</div>
												<span>Vouchers</span>
											</a>
										</div>
									</div>

									<div class="col-20">
										<div class="content">
											<a href="/recharge/">
												<div class="icon">
													<i class="fas fa-mobile-alt"></i>
												</div>
												<span>Recharge</span>
											</a>
										</div>
									</div>

									<!-- <div class="col-20">
										<div class="content">
											<a href="/healthcarepackage/">
												<div class="icon">
													<i class="fas fa-syringe"></i>
												</div>
												<span>Healthcare</span>
											</a>
										</div>
									</div> -->



									<div class="col-20">
										<div class="content">
											<a href="/all-categories/">
												<div class="icon">
													<i class="fas fa-th"></i>
												</div>
												<span>Show All</span>
											</a>
										</div>
									</div>
								</div>

							<!-- <div class="categories-details segments">
								<div class="card">
									<div class="card-content card-content-padding">
										<h5> Bookings</h5>
									</div>
								</div>
							</div>
 -->

								<div class="row">
									

									<div class="col-20">
										<div class="content">
											<a href="/trainbooking/">
												<div class="icon">
													<i class="fas fa-train"></i>
												</div>
												<span>Train</span>
											</a>
										</div>
									</div>
									<div class="col-20">
										<div class="content">
											<a href="/bus-booking/">
												<div class="icon">
													<i class="fas fa-bus"></i>
												</div>
												<span>Bus</span>
											</a>
										</div>
									</div>

									<div class="col-20">
										<div class="content">
											<a href="/trainbooking/">
												<div class="icon">
													<i class="fas fa-film"></i>
												</div>
												<span>Movie</span>
											</a>
										</div>
									</div>
									<div class="col-20">
										<div class="content">
											<a href="/flight-booking/">
												<div class="icon">
													<i class="fas fa-plane"></i>
												</div>
												<span>Flight</span>
											</a>
										</div>
									</div>

									<div class="col-20">
										<div class="content">
											<a href="/trainbooking/">
												<div class="icon">
													<i class="fas fa-hotel"></i>
												</div>
												<span>Hotel</span>
											</a>
										</div>
									</div>
									
									<!-- <div class="col-20">
										<div class="content">
											<a href="/franchisee/">
												<div class="icon">
													<i class="fas fa-blog"></i>
												</div>
												<span>Franchisee</span>
											</a>
										</div>
									</div>
									<div class="col-20">
										<div class="content">
											<a href="/demat/">
												<div class="icon">
													<i class="fas fa-user"></i>
												</div>
												<span>Demat</span>
											</a>
										</div>
									</div> -->
									
								


								</div>

						
       						<!--  <div class="categories-details segments">
								<div class="card">
									<div class="card-content card-content-padding">
										<h5> Recharge & Bills</h5>
									</div>
								</div>
							</div>
						 -->

								<div class="row">
									<!-- <div class="col-20">
										<div class="content">
											<a href="/recharge/">
												<div class="icon">
													<i class="fas fa-mobile-alt"></i>
												</div>
												<span>Mobile Recharge</span>
											</a>
										</div>
									</div> -->
									<div class="col-20">
										<div class="content">
											<a href="/recharge-bill-pay/">
												<div class="icon">
													<i class="fas fa-satellite-dish"></i>
												</div>
												<span>DTH</span>
											</a>
										</div>
									</div>
									<div class="col-20">
										<div class="content">
											<a href="/recharge-bill-pay/">
												<div class="icon">
													<i class="fas fa-tint"></i>
												</div>
												<span>Water</span>
											</a>
										</div>
									</div>
									<div class="col-20">
										<div class="content">
											<a href="/recharge-bill-pay/">
												<div class="icon">
													<i class="fas fa-credit-card"></i>
												</div>
												<span>Credit Card Bills</span>
											</a>
										</div>
									</div>
									<div class="col-20">
										<div class="content">
											<a href="/recharge-bill-pay/">
												<div class="icon">
													<i class="fas fa-lightbulb"></i>
												</div>
												<span>Electricity</span>
											</a>
										</div>
									</div>
									<div class="col-20">
										<div class="content">
											<a href="/internetrecharge/">
												<div class="icon">
													<i class="fas fa-network-wired"></i>
												</div>
												<span>Internet</span>
											</a>
										</div>
									</div>
									
								</div>
							</div>
						</div>
						<!-- end cateogries -->

						<!-- flash sale -->
						<div class="flash-sale segments no-pd-b">
							<div class="container">
								<div class="section-title flash-s-title">
									<h3>CASHOO BENEFITS</h3>
									<div data-space-between="10" data-slides-per-view="auto" class="swiper-container swiper-init">
										<div class="swiper-wrapper">
											<div class="swiper-slide">
												<div class="content content-shadow-product">
													<a href="/product-details/">
														<div class="product-mark-discount">
															<ul>
																<li>10% pm</li>
																<li>Cashback</li>
															</ul>
														</div>
														<img src="images/flash-sale1.jpg" alt="flash-sale">
														<div class="text">
															<p>PETROL</p>
														</div>
													</a>
												</div>
											</div>
											<div class="swiper-slide">
												<div class="content content-shadow-product">
													<a href="/product-details/">
														<div class="product-mark-discount">
															<ul>
																<li>10% pm</li>
																<li>Cashback</li>
															</ul>
														</div>
														<img src="images/flash-sale2.jpg" alt="flash-sale">
														<div class="text">
															<p>GROCERY</p>
														</div>
													</a>
												</div>
											</div>
											<div class="swiper-slide">
												<div class="content content-shadow-product">
													<a href="/product-details/">
														<div class="product-mark-discount">
															<ul>
																<li>10% pm</li>
																<li>Cashback</li>
															</ul>
														</div>
														<img src="images/flash-sale3.jpg" alt="flash-sale">
														<div class="text">
															<p>Health</p>
														</div>
													</a>
												</div>
											</div>
									<!-- 		<div class="swiper-slide">
												<div class="content content-shadow-product">
													<a href="/product-details/">
														<div class="product-mark-discount">
															<ul>
																<li>50%</li>
																<li>OFF</li>
															</ul>
														</div>
														<img src="images/flash-sale4.jpg" alt="flash-sale">
														<div class="text">
															<p>Rs.66 <span>Rs.133</span></p>
														</div>
													</a>
												</div>
											</div> -->
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- end flash sale -->

						<!-- popular product -->
						<!-- <div class="popular-product segments-bottom">
							<div class="container">
								<div class="section-title">
									<h3>Popular Product
										<a href="#" class="see-all-link">See All</a>
									</h3>
								</div>
								<div data-pagination='{"el": ".swiper-pagination"}' data-space-between="10" data-slides-per-view="3" class="swiper-container swiper-init">
									<div class="swiper-pagination"></div>
									<div class="swiper-wrapper">
										<div class="swiper-slide">
											<div class="content content-shadow-product">
												<a href="/product-details/">
													<img src="images/product1.jpg" alt="product">
													<div class="text">
														<p class="title-product">Sweater with latest model</p>
														<p class="price">Rs.85.00</p>
														<p class="location">New York</p>
													</div>
												</a>
											</div>
										</div>
										<div class="swiper-slide">
											<div class="content content-shadow-product">
												<a href="/product-details/">
													<img src="images/product2.jpg" alt="product">
													<div class="text">
														<p class="title-product">Latest smartwatch</p>
														<p class="price">Rs.66.00</p>
														<p class="location">New York</p>
													</div>
												</a>
											</div>
										</div>
										<div class="swiper-slide">
											<div class="content content-shadow-product">
												<a href="/product-details/">
													<img src="images/product3.jpg" alt="product">
													<div class="text">
														<p class="title-product">Jacket for adventure</p>
														<p class="price">Rs.80.99</p>
														<p class="location">New York</p>
													</div>
												</a>
											</div>
										</div>
										<div class="swiper-slide">
											<div class="content content-shadow-product">
												<a href="/product-details/">
													<img src="images/product4.jpg" alt="product">
													<div class="text">
														<p class="title-product">New game Console</p>
														<p class="price">Rs.49.00</p>
														<p class="location">New York</p>
													</div>
												</a>
											</div>
										</div>
										<div class="swiper-slide">
											<div class="content content-shadow-product">
												<a href="/product-details/">
													<img src="images/product5.jpg" alt="product">
													<div class="text">
														<p class="title-product">Cheap black shirt</p>
														<p class="price">Rs.50.00</p>
														<p class="location">New York</p>
													</div>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div> -->
						<!-- end popular product -->

						<!-- recommended -->
						<!-- <div class="recommended product segments-bottom">
							<div class="container">
								<div class="section-title">
									<h3>Recommended For You
										<a href="#" class="see-all-link">See All</a>
									</h3>
								</div>
								<div class="row">
									<div class="col-50">
										<div class="content content-shadow-product">
											<a href="/product-details/">
												<img src="images/product6.jpg" alt="product">
												<div class="text">
													<p class="title-product">Cheap elegant sweater with collar</p>
													<p class="price">Rs.80.00</p>
													<p class="location">New York</p>
												</div>
											</a>
										</div>
									</div>
									<div class="col-50">
										<div class="content content-shadow-product">
											<a href="/product-details/">
												<img src="images/product7.jpg" alt="product">
												<div class="text">
													<p class="title-product">Responsive mouse just fits in your hand</p>
													<p class="price">Rs.59.00</p>
													<p class="location">New York</p>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-50">
										<div class="content content-shadow-product">
											<a href="/product-details/">
												<img src="images/product8.jpg" alt="product">
												<div class="text">
													<p class="title-product">Loafers with genuine leather, guaranteed</p>
													<p class="price">Rs.299.99</p>
													<p class="location">New York</p>
												</div>
											</a>
										</div>
									</div>
									<div class="col-50">
										<div class="content content-shadow-product">
											<a href="/product-details/">
												<img src="images/product9.jpg" alt="product">
												<div class="text">
													<p class="title-product">Long-sleeved gray sweater, soft material</p>
													<p class="price">Rs.90.00</p>
													<p class="location">New York</p>
												</div>
											</a>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-50">
										<div class="content content-shadow-product">
											<a href="/product-details/">
												<img src="images/product10.jpg" alt="product">
												<div class="text">
													<p class="title-product">Thick jacket suitable for winter</p>
													<p class="price">Rs.145.00</p>
													<p class="location">New York</p>
												</div>
											</a>
										</div>
									</div>
									<div class="col-50">
										<div class="content content-shadow-product">
											<a href="/product-details/">
												<img src="images/product11.jpg" alt="product">
												<div class="text">
													<p class="title-product">Shirt with long sleeve collar - black</p>
													<p class="price">Rs.88.00</p>
													<p class="location">New York</p>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div> -->
						<!-- end recommended -->

						<!-- end home -->
					</div>
					<div id="tab-search" class="tab tab-search">
						<!-- search -->

						<!-- navbar -->
						<div class="navbar navbar-page">
							<div class="navbar-inner">
								<div class="left"></div>
								<div class="title">
									Search
								</div>
								<div class="right"></div>
							</div>
						</div>

						<div class="subnavbar">
							<form class="searchbar searchbar-init" data-search-container=".search-list" data-search-in=".item-title">
								<div class="searchbar-inner">
									<div class="searchbar-input-wrap">
										<input type="search" placeholder="Search for products for stores">
										<i class="searchbar-icon"></i>
										<span class="input-clear-button"></span>
									</div>
									<span class="searchbar-disable-button">Cancel</span>
								</div>
							</form>
						</div>

						<!-- end navbar -->

						<div class="search segments">
							<div class="container">
								<div class="title-search-category">
									<span>RECENT SEARCH <a href="#">Clear</a></span>
								</div>
								<div class="recent-search">
									<ul>
										<li><a href="#"><i class="fas fa-history"></i>Laptop accessories</a></li>
										<li><a href="#"><i class="fas fa-history"></i>Plain shirt</a></li>
										<li><a href="#"><i class="fas fa-history"></i>Gaming Mouse</a></li>
										<li><a href="#"><i class="fas fa-history"></i>Mechanical Keyboard</a></li>
									</ul>
								</div>

								<!-- divider -->
								<div class="divider-line-half"></div>
								<!-- end divider -->

								<div class="title-search-category">
									<span>POPULAR CATEGORIES</span>
								</div>
								<div class="popular-search">
									<ul>
										<li><a href="#">Laptop</a></li>
										<li><a href="#">Smartphones</a></li>
										<li><a href="#">Earphones</a></li>
										<li><a href="#">Mouse</a></li>
										<li><a href="#">Shirt</a></li>
										<li><a href="#">Keyboard</a></li>
										<li><a href="#">Bottle</a></li>
									</ul>
								</div>

								<!-- divider -->
								<div class="divider-line-half line-search"></div>
								<!-- end divider -->

								<div class="title-search-category">
									<span>RECOMMENDED <a href="#">Refresh</a></span>
								</div>
								<div class="recent-search recommended-search">
									<ul>
										<li><a href="#">Laptop accessories</a></li>
										<li><a href="#">Plain shirt</a></li>
										<li><a href="#">Gaming Mouse</a></li>
										<li><a href="#">Mechanical Keyboard</a></li>
									</ul>
								</div>
							</div>
						</div>

						<!-- end search -->
					</div>
					<div id="tab-brand" class="page-content tab">
						<!-- official brand -->

						<!-- navbar -->
						<div class="navbar navbar-page">
							<div class="navbar-inner">
								<div class="left"></div>
								<div class="title">
									Official Brand
								</div>
								<div class="right"></div>
							</div>
						</div>
						<!-- end navbar -->

						<div class="official-brand">
							<div class="container">

								<!-- slider brand -->
								<div class="slider-brand segments-bottom">
									<!-- <div data-pagination='{"el": ".swiper-pagination"}' data-space-between="10" class="swiper-container swiper-init swiper-container-horizontal">
										<div class="swiper-pagination"></div>
										<div class="swiper-wrapper">
											<div class="swiper-slide">
												<div class="content">
													<div class="mask"></div>
													<img src="images/banner4.png" alt="">
												</div>
											</div>
											<div class="swiper-slide">
												<div class="content">
													<div class="mask"></div>
													<img src="images/banner5.png" alt="">
												</div>
											</div>
											<div class="swiper-slide">
												<div class="content">
													<div class="mask"></div>
													<img src="images/banner6.png" alt="">
												</div>
											</div>
										</div>
									</div> -->
								</div>
								<!-- end slider brand -->

								<!-- popular brand -->
								<div class="popular-brand segments-bottom">
									<div class="section-title">
										<h3>Populars Brand <a href="#" class="see-all-link">See All</a></h3>
									</div>
									
									<div class="row">
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/green.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/zona.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/beauty.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/fitbro.png" alt=""></a>
											</div>
										</div>
									</div>
									
								</div>
								<!-- end popular brand -->
								<!-- popular brand -->
								<div class="popular-brand segments-bottom">
								
									<div class="section-title">
										<h3>Media Partner <a href="#" class="see-all-link">See All</a></h3>
									</div>
									<div class="row">
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/green.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/zona.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/beauty.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/fitbro.png" alt=""></a>
											</div>
										</div>
									</div>
									<div class="section-title">
										<h3>Digital Partner <a href="#" class="see-all-link">See All</a></h3>
									</div>
									<div class="row">
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/trip.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/wobag.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/john.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/alya.png" alt=""></a>
											</div>
										</div>
									</div>
									<div class="section-title">
										<h3>Channel Partner <a href="#" class="see-all-link">See All</a></h3>
									</div>
									<div class="row">
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/green.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/zona.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/beauty.png" alt=""></a>
											</div>
										</div>
										<div class="col-25">
											<div class="content">
												<a href="#"><img src="images/fitbro.png" alt=""></a>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
						<!-- end official brand -->
					</div>
					<div id="tab-cart" class="page-content tab">
						<!-- cart -->

						<!-- navbar -->
						<div class="navbar navbar-page">
							<div class="navbar-inner">
								<div class="left"></div>
								<div class="title">
									Shopping Cart
								</div>
								<div class="right"></div>
							</div>
						</div>
						<!-- end navbar -->

						<!-- content cart -->
						<div class="cart segments">
							<div class="container">
								<div class="name-store">
									<img src="images/user-seller1.png" alt="">
									<div class="title-store">
										<h6>ABC Shoes Store</h6>
									</div>
								</div>
								<div class="content">
									<div class="row">
										<div class="col-10">
											<div class="content-checkbox">
												<label class="checkbox">
													<input type="checkbox">
													<i class="icon-checkbox"></i>
												</label>
											</div>
										</div>
										<div class="col-90">
											<div class="content-cart content-shadow-product">
												<img src="images/product8.jpg" alt="">
												<div class="product-info">
													<a href="#"><p class="title-product">Loafers with genuine leather</p></a>
													<p class="price">Rs.50.00</p>
												</div>
												<div class="number-goods">
													<div class="stepper stepper-small stepper-init">
														<div class="stepper-button-minus"></div>
														<div class="stepper-input-wrap">
															<input type="text" value="0" readonly>
														</div>
														<div class="stepper-button-plus"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="content">
									<div class="row">
										<div class="col-10">
											<div class="content-checkbox">
												<label class="checkbox">
													<input type="checkbox">
													<i class="icon-checkbox"></i>
												</label>
											</div>
										</div>
										<div class="col-90">
											<div class="content-cart content-shadow-product">
												<img src="images/product12.jpg" alt="">
												<div class="product-info">
													<a href="#"><p class="title-product">The latest brand of loafers</p></a>
													<p class="price">Rs.50.00</p>
												</div>
												<div class="number-goods">
													<div class="stepper stepper-small stepper-init">
														<div class="stepper-button-minus"></div>
														<div class="stepper-input-wrap">
															<input type="text" value="0" readonly>
														</div>
														<div class="stepper-button-plus"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- divider line half -->
								<div class="divider-line-half"></div>
								<!-- end divider line half -->

								<div class="name-store">
									<img src="images/user-seller2.png" alt="">
									<div class="title-store">
										<h6>Shirt Mart</h6>
									</div>
								</div>
								<div class="content">
									<div class="row">
										<div class="col-10">
											<div class="content-checkbox">
												<label class="checkbox">
													<input type="checkbox">
													<i class="icon-checkbox"></i>
												</label>
											</div>
										</div>
										<div class="col-90">
											<div class="content-cart content-shadow-product">
												<img src="images/flash-sale1.jpg" alt="">
												<div class="product-info">
													<a href="#"><p class="title-product">Cheap, plain t-shirts</p></a>
													<p class="price">Rs.50.00</p>
												</div>
												<div class="number-goods">
													<div class="stepper stepper-small stepper-init">
														<div class="stepper-button-minus"></div>
														<div class="stepper-input-wrap">
															<input type="text" value="0" readonly>
														</div>
														<div class="stepper-button-plus"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- divider line full -->
							<div class="divider-line-full"></div>
							<!-- end divider line full -->

							<!-- wrap total cart -->
							<div class="wrap-total-cart">
								<div class="container">
									<div class="row">
										<div class="col-40">
											<div class="content-total">
												<p>Total</p>
												<h6>Rs.150</h6>
											</div>
										</div>
										<div class="col-60">
											<div class="content-button">
												<a href="/checkout/" class="button primary-button"><i class="fas fa-shopping-bag"></i>Checkout</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- end wrap total cart -->
						</div>
						<!-- end content cart -->

						<!-- end cart -->
					</div>
					<div id="tab-account" class="page-content tab">
						<!-- account buyer -->

						<!-- navbar -->
						<div class="navbar navbar-page">
							<div class="navbar-inner">
								<div class="left"></div>
								<div class="title">
									My Account
								</div>
								<div class="right">
									<a href="/settings/">
										<i class="fas fa-cog"></i>
									</a>
								</div>
							</div>
						</div>
						<!-- end navbar -->

						<!-- content account -->
						<div class="account-buyer segments">
							<div class="container">
								<div class="header-account content-shadow">
									<img src="images/user-buyer6.png" alt="">
									<div class="title-name">
										<h5>Airy Fashion</h5>
										<p><i class="fas fa-map-marker-alt"></i>Maharashtra</p>
									</div>
								</div>
							</div>
							<div class="container segments">
								<div class="info-balance content-shadow">
									<div class="row">
										<div class="col-50">
											<div class="content-text">
												<p>Your Balance</p>
												<h5>Rs.310.00</h5>
											</div>
										</div>
										<div class="col-50">
											<div class="content-button">
												<a href="https://sdv71shops.com/zestnew/site/login" class="external button primary-button"><i class="fas fa-wallet"></i>My Account</a>
												
											</div>
										</div> 
									</div>
								</div>
							</div>
							<div class="account-menu">
								<div class="list media-list">
									<ul>
										<li>
											<a href="/sign-in/" class="item-link item-content">
												<div class="item-media">
													<i class="fas fa-sign-in-alt"></i>
												</div>
												<div class="item-inner">
													<div class="item-title-row">
														<div class="item-title">Sign In</div>
													</div>
													<div class="item-subtitle">Open your account here.</div>
												</div>
											</a>
										</li>
										<li>
											<a href="/sign-up/" class="item-link item-content">
												<div class="item-media">
													<i class="fas fa-user-plus"></i>
												</div>
												<div class="item-inner">
													<div class="item-title-row">
														<div class="item-title">Sign Up</div>
													</div>
													<div class="item-subtitle">Create your account here.</div>
												</div>
											</a>
										</li>
										<li>
											<a href="/franchisee-sign-up/" class="item-link item-content">
												<div class="item-media">
													<i class="fas fa-layer-group"></i>
												</div>
												<div class="item-inner">
													<div class="item-title-row">
														<div class="item-title">Franchisee</div>
													</div>
													<div class="item-subtitle">Partnership</div>
												</div>
											</a>
										</li>
										<li>
											<a href="/wishlist/" class="item-link item-content">
												<div class="item-media">
													<i class="fas fa-heart"></i>
												</div>
												<div class="item-inner">
													<div class="item-title-row">
														<div class="item-title">Wishlist</div>
													</div>
													<div class="item-subtitle">All products that you have saved</div>
												</div>
											</a>
										</li>
										<li>
											<a href="/transaction/" class="item-link item-content">
												<div class="item-media">
													<i class="fas fa-exchange-alt"></i>
												</div>
												<div class="item-inner">
													<div class="item-title-row">
														<div class="item-title">Transaction</div>
													</div>
													<div class="item-subtitle">All your transactions are here</div>
												</div>
											</a>
										</li>
										<li>
											<a href="/notification/" class="item-link item-content">
												<div class="item-media">
													<i class="fas fa-bell"></i>
												</div>
												<div class="item-inner">
													<div class="item-title-row">
														<div class="item-title">Notification</div>
													</div>
													<div class="item-subtitle">Transaction, Purchase, Notification update</div>
												</div>
											</a>
										</li>
										<li>
											<a href="/faq/" class="item-link item-content">
												<div class="item-media">
													<i class="fas fa-question"></i>
												</div>
												<div class="item-inner">
													<div class="item-title-row">
														<div class="item-title">Help</div>
													</div>
													<div class="item-subtitle">Need Help, Frequently Asked Questions</div>
												</div>
											</a>
										</li>
										<li>
											<a href="/contact-seller/" class="item-link item-content">
												<div class="item-media">
													<i class="fas fa-envelope"></i>
												</div>
												<div class="item-inner">
													<div class="item-title-row">
														<div class="item-title">Contact Seller</div>
													</div>
													<div class="item-subtitle">Other questions can contact me</div>
												</div>
											</a>
										</li>
										<li>
											<a href="#" class="item-link item-content">
												<div class="item-media">
													<i class="fas fa-power-off"></i>
												</div>
												<div class="item-inner">
													<div class="item-title">Logout</div>
												</div>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- end  content account -->

						<!-- end account buyer -->
					</div>
				</div>
			</div>
		</div>

	</div>

	<script src="js/framework7.bundle.min.js"></script>
	<script src="js/routes.js"></script>
	<script src="js/app.js"></script>

</body>
</html>