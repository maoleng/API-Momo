<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/init.php');
if(is_client()){
    load_url('/quanly');
}

?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="icon" type="image/png" href="/assets/img/junoo.png" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Login || fgocheat.net</title>
        <!-- Required Meta Tags Always Come First -->
        <meta name="csrf-token" content="<?= $_SESSION['csrf-token'];?>" />
        <meta name="keywords" content="fgocheat.net" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="fgocheat.net" />
        <meta name="author" content="fgocheat.net" />
        <meta name="copyright" content="fgocheat.net - We Design Your Future <3" />
        <meta name="generator" content="fgocheat.net - We Design Your Future <3" />
        <meta name="robots" content="index, follow" />
        <!-- Facebook -->
        <meta property="og:title" content="fgocheat.net - We Design Your Future <3" />
        <meta property="og:description" content="Developed by fgocheat.net" />
        <meta property="og:url" content="https://fgocheat.net" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="/assets/img/junoo.png" />
        <meta property="og:image:width" content="600" />
        <meta property="og:image:height" content="315" />
        <meta property="og:image:alt" content="Developed by fgocheat.net" />
        <meta property="og:site_name" content="fgocheat.net" />
        <meta property="og:locale" content="vi_VN" />
        <meta property="fb:admins" content="100040290924356" />
        <!-- Canonical SEO -->
        <link rel="canonical" href="https://fgocheat.net" />
        <!--  Social tags      -->
        <meta name="keywords" content="ninja school online,ban xu,lam web ban nick,ban xu tu dongm" />
        <meta name="description" content="fgocheat.net - We Design Your Future <3" />
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="fgocheat.net - We Design Your Future <3" />
        <meta itemprop="description" content="fgocheat.net - We Design Your Future <3" />
        <meta itemprop="image" content="/assets/img/junoo.png" />
        <!-- Twitter Card data -->
        <meta name="twitter:card" content="product" />
        <meta name="twitter:site" content="@junoo" />
        <meta name="twitter:title" content="fgocheat.net - We Design Your Future <3" />
        <meta name="twitter:description" content="fgocheat.net - We Design Your Future <3" />
        <meta name="twitter:creator" content="@junoo" />
        <meta name="twitter:image" content="/assets/img/junoo.png" />

		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Custom Styles(used by this page)-->
		<link href="/assets/css/pages/login/classic/login-649d8.css?v=7.2.8" rel="stylesheet" type="text/css" />
		<!--end::Page Custom Styles-->
				<!--begin::Global Theme Styles(used by all pages)-->
		<link href="/assets/plugins/global/plugins.bundle49d8.css?v=7.2.8" rel="stylesheet" type="text/css" />
		<link href="/assets/plugins/custom/prismjs/prismjs.bundle49d8.css?v=7.2.8" rel="stylesheet" type="text/css" />
		<link href="/assets/css/style.bundle49d8.css?v=7.2.8" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<link href="/assets/css/themes/layout/header/base/light49d8.css?v=7.2.8" rel="stylesheet" type="text/css" />
		<link href="/assets/css/themes/layout/header/menu/light49d8.css?v=7.2.8" rel="stylesheet" type="text/css" />
		<link href="/assets/css/themes/layout/brand/dark49d8.css?v=7.2.8" rel="stylesheet" type="text/css" />
		<link href="/assets/css/themes/layout/aside/dark49d8.css?v=7.2.8" rel="stylesheet" type="text/css" />
		<!--end::Layout Themes-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-6 login-signin-on login-signin-on d-flex flex-column-fluid" id="kt_login">
				<div class="d-flex flex-column flex-lg-row flex-row-fluid text-center" style="background-image: url(/assets/media/bg/bg-3.jpg?v=1);">
					<!--begin:Aside-->
					<div class="d-flex w-100 flex-center p-15">
						<div class="login-wrapper">
							<!--begin:Aside Content-->
							<div class="text-dark-75">
								<a href="#">
									<img src="/assets/media/logos/logo-letter-13.png?v=1" class="max-h-75px" alt="" />
								</a>
								<h3 class="mb-8 mt-22 font-weight-bold">JOIN OUR GREAT COMMUNITY</h3>
								<p class="mb-15 text-muted font-weight-bold">Hệ thống api hàng đầu Việt Nam.</p>
								<a href="//fgocheat.net"><button type="button" class="btn btn-outline-primary btn-pill py-4 px-9 font-weight-bold">Contact</button></a>
							</div>
							<!--end:Aside Content-->
						</div>
					</div>
					<!--end:Aside-->
					<!--begin:Divider-->
					<div class="login-divider">
						<div></div>
					</div>
					<!--end:Divider-->
					<!--begin:Content-->
					<div class="d-flex w-100 flex-center p-15 position-relative overflow-hidden">
						<div class="login-wrapper">
							<!--begin:Sign In Form-->
							<div class="login-signin">
								<div class="text-center mb-10 mb-lg-20">
									<h2 class="font-weight-bold">Sign In</h2>
									<p class="text-muted font-weight-bold">Enter your username and password</p>
								</div>
								<form class="form text-left" id="kt_login_signin_form">
								    <input name="_token" type="hidden" value="<?php echo $_SESSION['csrf-token'];?>" />
									<div class="form-group py-2 m-0">
										<input class="form-control h-auto border-0 px-0 placeholder-dark-75" type="text" placeholder="Username" name="username" autocomplete="off" />
									</div>
									<div class="form-group py-2 border-top m-0">
										<input class="form-control h-auto border-0 px-0 placeholder-dark-75" type="Password" placeholder="Password" name="password" />
									</div>
									<div class="form-group d-flex flex-wrap justify-content-between align-items-center mt-5">
										<div class="checkbox-inline">
											<label class="checkbox m-0 text-muted font-weight-bold">
											<input type="checkbox" name="remember" />
											<span></span>Remember me</label>
										</div>
										<a href="//fgocheat.net" id="kt_login_forgot" class="text-muted text-hover-primary font-weight-bold">Contact</a>
									</div>
									<div class="text-center mt-15">
										<button id="kt_login_signin_submit" class="btn btn-primary btn-pill shadow-sm py-4 px-9 font-weight-bold">Sign In</button>
									</div>
								</form>
							</div>
							<!--end:Sign In Form-->
							
						</div>
					</div>
					<!--end:Content-->
				</div>
			</div>
			<!--end::Login-->
		</div>
		<!--end::Main-->
		<script>var HOST_URL = "";</script>
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="/assets/plugins/global/plugins.bundle49d8.js?v=7.2.8"></script>
		<script src="/assets/plugins/custom/prismjs/prismjs.bundle49d8.js?v=7.2.8"></script>
		<script src="/assets/js/scripts.bundle49d8.js?v=7.2.8"></script>
		<!--end::Global Theme Bundle-->
		<script src="/assets/js/pages/custom/login/login-general49d8.js?v=7.3.3"></script>
	</body>
	<!--end::Body-->
</html>