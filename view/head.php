<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/init.php');
if(!is_client()){
    load_url('/');
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
        <title>Hệ Thống Api Tự Động || Junoo</title>
        <!-- Required Meta Tags Always Come First -->
        <meta name="csrf-token" content="<?= $_SESSION['csrf-token'];?>" />
        <meta name="keywords" content="Vũ Tùng Duy,Junoo.Net,junoonet,Junoo,junoo,vutungduy,vu tung duy,ninja school online,ban xu,lam web ban nick,ban xu tu dong" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Vũ Tùng Duy - Junoo" />
        <meta name="author" content="Vũ Tùng Duy - Junoo" />
        <meta name="copyright" content="Junoo.Net - We Design Your Future <3" />
        <meta name="generator" content="Junoo.Net - We Design Your Future <3" />
        <meta name="robots" content="index, follow" />
        <!-- Facebook -->
        <meta property="og:title" content="Junoo.Net - We Design Your Future <3" />
        <meta property="og:description" content="Developed by Vũ Tùng Duy - Junoo" />
        <meta property="og:url" content="https://junoo.net" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="/assets/img/junoo.png" />
        <meta property="og:image:width" content="600" />
        <meta property="og:image:height" content="315" />
        <meta property="og:image:alt" content="Developed by Vũ Tùng Duy - Junoo" />
        <meta property="og:site_name" content="Junoo.Net" />
        <meta property="og:locale" content="vi_VN" />
        <meta property="fb:admins" content="100040290924356" />
        <!-- Canonical SEO -->
        <link rel="canonical" href="https://junoo.net" />
        <!--  Social tags      -->
        <meta name="keywords" content="ninja school online,ban xu,lam web ban nick,ban xu tu dong" />
        <meta name="description" content="Junoo.Net - We Design Your Future <3" />
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="Junoo.Net - We Design Your Future <3" />
        <meta itemprop="description" content="Junoo.Net - We Design Your Future <3" />
        <meta itemprop="image" content="/assets/img/junoo.png" />
        <!-- Twitter Card data -->
        <meta name="twitter:card" content="product" />
        <meta name="twitter:site" content="@junoo" />
        <meta name="twitter:title" content="Junoo.Net - We Design Your Future <3" />
        <meta name="twitter:description" content="Junoo.Net - We Design Your Future <3" />
        <meta name="twitter:creator" content="@junoo" />
        <meta name="twitter:image" content="/assets/img/junoo.png" />

		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<?php if($_SERVER['REQUEST_URI'] == '/quanly') { ?>
		<link href="/assets/plugins/custom/fullcalendar/fullcalendar.bundle49d8.css?v=7.2.8" rel="stylesheet" type="text/css" />
		<?php } ?>
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
		<link rel="shortcut icon" href="https://preview.keenthemes.com/metronic/theme/html/demo1/dist/assets/media/logos/favicon.ico" />
		
		
		<style>
            #loading {
                background-color:white;
                position: fixed;
                display: block;
                top: 0;
                bottom: 0;
                z-index: 1000000;
                opacity: 0.5;
                width: 100%;
                height: 100%;
                text-align: center;
            }

            #loading img {
                margin: auto;
                display: block;
                top: calc(50% - 100px);
                left: calc(50% - 10px);
                position: absolute;
                z-index: 999999;
            }
		</style>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		<div id="loading" style="display:none">
            <img src="/assets/images/load.gif" alt="Loading..."/>
        </div>
		<!--begin::Main-->
		<!--begin::Header Mobile-->
		<div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">
			<!--begin::Logo-->
			<a href="/quanly">
				<img alt="Logo" src="/assets/media/logos/logo-light.png" />
			</a>
			<!--end::Logo-->
			<!--begin::Toolbar-->
			<div class="d-flex align-items-center">
				<!--begin::Aside Mobile Toggle-->
				<button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
					<span></span>
				</button>
				<!--end::Aside Mobile Toggle-->
				<!--begin::Header Menu Mobile Toggle-->
				<button class="btn p-0 burger-icon ml-4" id="kt_header_mobile_toggle">
					<span></span>
				</button>
				<!--end::Header Menu Mobile Toggle-->
				<!--begin::Topbar Mobile Toggle-->
				<button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
					<span class="svg-icon svg-icon-xl">
						<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/General/User.svg-->
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<polygon points="0 0 24 0 24 24 0 24" />
								<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
								<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
							</g>
						</svg>
						<!--end::Svg Icon-->
					</span>
				</button>
				<!--end::Topbar Mobile Toggle-->
			</div>
			<!--end::Toolbar-->
		</div>
		<!--end::Header Mobile-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="d-flex flex-row flex-column-fluid page">
				<!--begin::Aside-->
				<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
					<!--begin::Brand-->
					<div class="brand flex-column-auto" id="kt_brand">
						<!--begin::Logo-->
						<a href="/quanly" class="brand-logo">
							<img alt="Logo" src="/assets/media/logos/logo-light.png" />
						</a>
						<!--end::Logo-->
						<!--begin::Toggle-->
						<button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
							<span class="svg-icon svg-icon svg-icon-xl">
								<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Navigation/Angle-double-left.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<polygon points="0 0 24 0 24 24 0 24" />
										<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
										<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</button>
						<!--end::Toolbar-->
					</div>
					<!--end::Brand-->
					<!--begin::Aside Menu-->
					<div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
						<!--begin::Menu Container-->
						<div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
							<!--begin::Menu Nav-->
							<ul class="menu-nav">
								<li class="menu-item <?php if($_SERVER['REQUEST_URI'] == '/quanly') { ?>menu-item-open <?php } ?>" aria-haspopup="true">
									<a href="/quanly" class="menu-link">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Layers.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
													<path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-text">Trang Chủ</span>
									</a>
								</li>
								<li class="menu-section">
									<h4 class="menu-text">Momo</h4>
									<i class="menu-icon ki ki-bold-more-hor icon-md"></i>
								</li>
								<li class="menu-item menu-item-submenu <?php if(strpos($_SERVER['REQUEST_URI'], "/quanly/momo/topup") !== false || $_SERVER['REQUEST_URI'] == '/quanly/momo/histories' || $_SERVER['REQUEST_URI'] == '/quanly/momo/send' || $_SERVER['REQUEST_URI'] == '/quanly/momo/send_bank'  || strpos($_SERVER['REQUEST_URI'], "/momo/momo_account") !== false) { ?>menu-item-open <?php } ?>" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
													<path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-text">Quản Lý Momo</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
										    <li class="menu-item <?php if($_SERVER['REQUEST_URI'] == '/quanly/momo/histories') { ?>menu-item-active<?php } ?>" aria-haspopup="true">
															<a href="/quanly/momo/histories" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">Lịch Sử Nhận Tiền</span>
															</a>
											</li>
											<li class="menu-item <?php if($_SERVER['REQUEST_URI'] == '/quanly/momo/send') { ?>menu-item-active<?php } ?>" aria-haspopup="true">
															<a href="/quanly/momo/send" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">Gửi Tiền Đến Momo</span>
															</a>
														</li>
											<li class="menu-item <?php if($_SERVER['REQUEST_URI'] == '/quanly/momo/send_bank') { ?>menu-item-active<?php } ?>" aria-haspopup="true">
															<a href="/quanly/momo/send_bank" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">Gửi Tiền Đến Bank</span>
															</a>
														</li>
											<li class="menu-item <?php if(strpos($_SERVER['REQUEST_URI'], "/momo/momo_account") !== false) { ?>menu-item-active<?php } ?>" aria-haspopup="true">
															<a href="/quanly/momo/momo_account" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">Quản Lý Tài Khoản</span>
															</a>
														</li>
											<li class="menu-item <?php if(strpos($_SERVER['REQUEST_URI'], "/quanly/momo/topup") !== false) { ?>menu-item-active<?php } ?>" aria-haspopup="true">
															<a href="/quanly/momo/topup" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">Nạp Tiền</span>
															</a>
														</li>
											<li class="menu-item" aria-haspopup="true">
															<a href="#" onclick="if (confirm('Bạn có chắc muốn reset hạn mức,hành động không thể khôi phục')){return resetActions('resetDay');}" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">Reset Hạn Mức Ngày</span>
															</a>
											</li>
											<li class="menu-item" aria-haspopup="true">
															<a href="#" onclick="if (confirm('Bạn có chắc muốn reset hạn mức,hành động không thể khôi phục')){return resetActions('resetMonth');}" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">Reset Hạn Mức Tháng</span>
															</a>
											</li>
										</ul>
									</div>
								</li>
                                <!-- Bank Zone --> 
								<li class="menu-section">
									<h4 class="menu-text">Bank Zone</h4>
									<i class="menu-icon ki ki-bold-more-hor icon-md"></i>
								</li>
								<li class="menu-item menu-item-submenu <?php if(strpos($_SERVER['REQUEST_URI'], "/quanly/bank/accounts") !== false || strpos($_SERVER['REQUEST_URI'], "/quanly/bank/vietcombank/histories") !== false || strpos($_SERVER['REQUEST_URI'], "/quanly/bank/mbbank/histories") !== false || strpos($_SERVER['REQUEST_URI'], "/quanly/bank/acb/histories") !== false) { ?>menu-item-open <?php } ?>" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon svg-icon-primary svg-icon-2x">
										    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Loader.svg-->
										    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M8,4 C8.55228475,4 9,4.44771525 9,5 L9,17 L18,17 C18.5522847,17 19,17.4477153 19,18 C19,18.5522847 18.5522847,19 18,19 L9,19 C8.44771525,19 8,18.5522847 8,18 C7.44771525,18 7,17.5522847 7,17 L7,6 L5,6 C4.44771525,6 4,5.55228475 4,5 C4,4.44771525 4.44771525,4 5,4 L8,4 Z" fill="#000000" opacity="0.3"/>
                                                    <rect fill="#000000" opacity="0.3" x="11" y="7" width="8" height="8" rx="4"/>
                                                    <circle fill="#000000" cx="8" cy="18" r="3"/>
                                                </g>
                                            </svg><!--end::Svg Icon-->
                                        </span>
										<span class="menu-text">Bank</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
										    <li class="menu-item <?php if(strpos($_SERVER['REQUEST_URI'], "/quanly/bank/accounts") !== false ) { ?>menu-item-active<?php } ?>" aria-haspopup="true">
															<a href="/quanly/bank/accounts" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">Quản lí tài khoản</span>
															</a>
														</li>
										    <li class="menu-item <?php if(strpos($_SERVER['REQUEST_URI'], "/quanly/bank/vietcombank/histories") !== false ) { ?>menu-item-active<?php } ?>" aria-haspopup="true">
															<a href="/quanly/bank/vietcombank/histories" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">VietComBank Histories</span>
															</a>
														</li>
											<li class="menu-item <?php if(strpos($_SERVER['REQUEST_URI'], "/quanly/bank/mbbank/histories") !== false) { ?>menu-item-active<?php } ?>" aria-haspopup="true">
															<a href="/quanly/bank/mbbank/histories" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">MBBank Histories</span>
															</a>
														</li>
											<li class="menu-item <?php if(strpos($_SERVER['REQUEST_URI'], "/quanly/bank/acb/histories") !== false) { ?>menu-item-active<?php } ?>" aria-haspopup="true">
															<a href="/quanly/bank/acb/histories" class="menu-link">
																<i class="menu-bullet menu-bullet-dot">
																	<span></span>
																</i>
																<span class="menu-text">ACB Histories</span>
															</a>
														</li>
										</ul>
									</div>
								</li>
								<!-- end Bank Zone -->
								
							</ul>
							<!--end::Menu Nav-->
						</div>
						<!--end::Menu Container-->
					</div>
					<!--end::Aside Menu-->
				</div>
				<!--end::Aside-->
				<!--begin::Wrapper-->
				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
					<!--begin::Header-->
					<div id="kt_header" class="header header-fixed">
						<!--begin::Container-->
						<div class="container-fluid d-flex align-items-stretch justify-content-between">
							<!--begin::Header Menu Wrapper-->
							<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
								<!--begin::Header Menu-->
								<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
									<!--begin::Header Nav-->
									<ul class="menu-nav">
										
									
									</ul>
									<!--end::Header Nav-->
								</div>
								<!--end::Header Menu-->
							</div>
							<!--end::Header Menu Wrapper-->
							<!--begin::Topbar-->
							<div class="topbar">

								
								<!--begin::User-->
								<div class="topbar-item">
									<div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
										<span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
										<span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3"><?=$accounts['name']?></span>
										<span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
											<span class="symbol-label font-size-h5 font-weight-bold">D</span>
										</span>
									</div>
								</div>
								<!--end::User-->
							</div>
							<!--end::Topbar-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->
					
					