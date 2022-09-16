<?php
   require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/init.php');
   require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/view/head.php');
   if(!is_client()){
       load_url('/');
   }
?>

<!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	  <!--begin::Subheader-->
	  <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
	      <!--begin::Info-->
	      <div class="d-flex align-items-center flex-wrap mr-1">
	        <!--begin::Page Heading-->
	        <div class="d-flex align-items-baseline flex-wrap mr-5">
	          <!--begin::Page Title-->
	          <h5 class="text-dark font-weight-bold my-1 mr-5">Quản Lý Momo</h5>
	          <!--end::Page Title-->
	          <!--begin::Breadcrumb-->
	          <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
	            <!--<li class="breadcrumb-item text-muted"><a href="" class="text-muted">Features</a></li><li class="breadcrumb-item text-muted"><a href="" class="text-muted">Layout Builder</a></li>-->
	          </ul>
	          <!--end::Breadcrumb-->
	        </div>
	        <!--end::Page Heading-->
	      </div>
	      <!--end::Info-->
	      <!--begin::Toolbar-->
	      <div class="d-flex align-items-center"></div>
	      <!--end::Toolbar-->
	    </div>
	  </div>
	  <!--end::Subheader-->
						<!--begin::Entry-->
						<div class="d-flex flex-column-fluid">
							<!--begin::Container-->
							<div class="container">
							    <!--Begin::Row-->
								<div class="row">
									<div class="col-xl-3">
										<!--begin::Stats Widget 29-->
										<div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(/metronic/theme/html/demo1/dist/assets/media/svg/shapes/abstract-1.svg)">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-info">
													<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Mail-opened.svg-->
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<rect x="0" y="0" width="24" height="24" />
															<path d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z" fill="#000000" opacity="0.3" />
															<path d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z" fill="#000000" />
														</g>
													</svg>
													<!--end::Svg Icon-->
												</span>
												<?php
												    $totalRecive = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE  status_momo = '999' AND `user_id` = '".$accounts['username']."'") ?: 0;
												?>
												<span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block"><?=number_format($totalRecive)?><sup>VNĐ</sup></span>
												<span class="font-weight-bold text-muted font-size-sm">Tổng Tiền Nhận</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 29-->
									</div>
									<div class="col-xl-3">
										<!--begin::Stats Widget 30-->
										<div class="card card-custom bg-info card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-white">
													<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Group.svg-->
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<polygon points="0 0 24 0 24 24 0 24" />
															<path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
															<path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
														</g>
													</svg>
													<!--end::Svg Icon-->
												</span>
												<?php
												    $totalSend = $db->fetch_row("SELECT SUM(amount) FROM send WHERE  status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
												?>
												<span class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block"><?=number_format($totalSend)?><sup>VNĐ</sup></span>
												<span class="font-weight-bold text-white font-size-sm">Tổng Tiền Gửi</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 30-->
									</div>
									<div class="col-xl-3">
										<!--begin::Stats Widget 31-->
										<div class="card card-custom bg-danger card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-white">
													<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Media/Equalizer.svg-->
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<rect x="0" y="0" width="24" height="24" />
															<rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5" />
															<rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5" />
															<rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5" />
															<rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5" />
														</g>
													</svg>
													<!--end::Svg Icon-->
												</span>
												<?php
												    $totalMomo = $db->fetch_row("SELECT COUNT(id) FROM `cron_momo` WHERE  `status` = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
												?>
												<span class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block"><?=number_format($totalMomo)?></span>
												<span class="font-weight-bold text-white font-size-sm">Tổng Số Momo</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 31-->
									</div>
									<div class="col-xl-3">
										<!--begin::Stats Widget 32-->
										<div class="card card-custom bg-dark card-stretch gutter-b">
											<!--begin::Body-->
											<div class="card-body">
												<span class="svg-icon svg-icon-2x svg-icon-white">
													<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Group-chat.svg-->
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
															<rect x="0" y="0" width="24" height="24" />
															<path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000" />
															<path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3" />
														</g>
													</svg>
													<!--end::Svg Icon-->
												</span>
												<?php
												    $totalMoneyMomo = $db->fetch_row("SELECT SUM(BALANCE) FROM cron_momo WHERE  status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
												?>
												<span class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 text-hover-primary d-block"><?=number_format($totalMoneyMomo)?><sup>VNĐ</sup></span>
												<span class="font-weight-bold text-white font-size-sm">Tổng Tiền Momo</span>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Stats Widget 32-->
									</div>
								</div>
								<!--End::Row-->

								<!--begin::Row-->
								<div class="row">
									<div class="col-xl-6">
										<!--begin::Charts Widget 1-->
										<div class="card card-custom card-stretch gutter-b">
											<!--begin::Header-->
											<div class="card-header h-auto border-0">
												<!--begin::Title-->
												<div class="card-title py-5">
													<h3 class="card-label">
														<span class="d-block text-dark font-weight-bolder">Thống Kê Ngày</span>
														<span class="d-block text-muted mt-2 font-size-sm"></span>
													</h3>
												</div>
												<!--end::Title-->
												<!--begin::Toolbar-->
												<div class="card-toolbar">
													<ul class="nav nav-pills nav-pills-sm nav-dark-75" role="tablist">
														
														<li class="nav-item">
															<a class="nav-link py-2 px-4 active" data-toggle="tab" href="#dayAnalytics_tab_3">
																<span class="nav-text font-size-sm">Day</span>
															</a>
														</li>
													</ul>
												</div>
												<!--end::Toolbar-->
											</div>
											<!--end::Header-->
											<!--begin::Body-->
											<div class="card-body">
												<!--begin::Chart-->
												<div id="dayAnalytics"></div>
												<!--end::Chart-->
											</div>
											<!--end::Body-->
										</div>
										<!--end::Charts Widget 1-->
									</div>
									<div class="col-xl-6">
										<!--begin::Charts Widget 2-->
										<div class="card card-custom bg-gray-100 card-stretch gutter-b">
											<!--begin::Header-->
											<div class="card-header h-auto border-0">
												<!--begin::Title-->
												<div class="card-title py-5">
													<h3 class="card-label">
														<span class="d-block text-dark font-weight-bolder">Thống Kê Tháng</span>
														<span class="d-block text-dark-50 mt-2 font-size-sm"></span>
													</h3>
												</div>
												<!--end::Title-->
												<!--begin::Toolbar-->
												<div class="card-toolbar">
													<ul class="nav nav-pills nav-pills-sm nav-dark-75" role="tablist">
														<li class="nav-item">
															<a class="nav-link py-2 px-4 active" data-toggle="tab" href="#kt_charts_widget_2_chart_tab_1">
																<span class="nav-text font-size-sm">Month</span>
															</a>
														</li>
														
													</ul>
												</div>
												<!--end::Toolbar-->
											</div>
											<!--end::Header-->
											<!--begin::Body-->
											<div class="card-body">
												<!--begin::Chart-->
												<div id="kt_charts_widget_2_chart"></div>
												<!--end::Chart-->
											</div>
											<!--end::Body-->
										</div>
										<!--end::Charts Widget 2-->
									</div>
								</div>
								<!--end::Row-->

								<!--end::Dashboard-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Entry-->
					</div>
<script>
    var _initChartsWidget1 = function () {
        var element = document.getElementById("dayAnalytics");

        if (!element) {
            return;
        }

        var options = {
            
            series: [{
                name: 'Tiền Nhận',
                <?php
                    $totalDay1 = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE day(created_at) = day(NOW()) AND month(created_at) = month(NOW()) AND year(created_at) = year(NOW()) AND status_momo = '999' AND `user_id` = '".$accounts['username']."' ") ?: 0;
                    $totalDay2 = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE `time_tran` >= '".getStartNgay(1)."'  AND `time_tran` <= '".getFinishNgay(1)."'  AND status_momo = '999' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay3 = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE `time_tran` >= '".getStartNgay(2)."'  AND `time_tran` <= '".getFinishNgay(2)."' AND status_momo = '999' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay4 = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE `time_tran` >= '".getStartNgay(3)."'  AND `time_tran` <= '".getFinishNgay(3)."' AND status_momo = '999' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay5 = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE  `time_tran` >= '".getStartNgay(4)."'  AND `time_tran` <= '".getFinishNgay(4)."' AND status_momo = '999' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay6 = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE `time_tran` >= '".getStartNgay(5)."'  AND `time_tran` <= '".getFinishNgay(5)."' AND status_momo = '999' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay7 = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE `time_tran` >= '".getStartNgay(6)."'  AND `time_tran` <= '".getFinishNgay(6)."'  AND status_momo = '999' AND `user_id` = '".$accounts['username']."'") ?: 0;

                ?>
                data: [<?=$totalDay7?>, <?=$totalDay6?>, <?=$totalDay5?>, <?=$totalDay4?>, <?=$totalDay3?>, <?=$totalDay2?> , <?=$totalDay1?>]
            }, {
                name: 'Tiền Gửi',
                <?php
                    $totalDay1 = $db->fetch_row("SELECT SUM(amount) FROM send WHERE day(date_time) = day(NOW()) AND month(date_time) = month(NOW()) AND year(date_time) = year(NOW())  AND status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay2 = $db->fetch_row("SELECT SUM(amount) FROM send WHERE `time` >= '".getStartNgay(1)."'  AND `time` <= '".getFinishNgay(1)."'  AND status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay3 = $db->fetch_row("SELECT SUM(amount) FROM send WHERE `time` >= '".getStartNgay(2)."'  AND `time` <= '".getFinishNgay(2)."'  AND status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay4 = $db->fetch_row("SELECT SUM(amount) FROM send WHERE `time` >= '".getStartNgay(3)."'  AND `time` <= '".getFinishNgay(3)."'   AND status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay5 = $db->fetch_row("SELECT SUM(amount) FROM send WHERE `time` >= '".getStartNgay(4)."'  AND `time` <= '".getFinishNgay(4)."'   AND status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay6 = $db->fetch_row("SELECT SUM(amount) FROM send WHERE `time` >= '".getStartNgay(5)."'  AND `time` <= '".getFinishNgay(5)."'   AND status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalDay7 = $db->fetch_row("SELECT SUM(amount) FROM send WHERE `time` >= '".getStartNgay(6)."'  AND `time` <= '".getFinishNgay(6)."'   AND status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
                ?>
                data: [<?=($totalDay7)?>, <?=($totalDay6)?>, <?=($totalDay5)?>, <?=($totalDay4)?>, <?=($totalDay3)?>, <?=($totalDay2)?>, <?=($totalDay1)?>]
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: ['30%'],
                    endingShape: 'rounded'
                },
            },
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                
                categories: ['<?=date("Y-m-d", strtotime("-6 day"))?>', '<?=date("Y-m-d", strtotime("-5 day"))?>', '<?=date("Y-m-d", strtotime("-4 day"))?>', '<?=date("Y-m-d", strtotime("-3 day"))?>', '<?=date("Y-m-d", strtotime("-2 day"))?>', '<?=date("Y-m-d", strtotime("-1 day"))?>','<?=date("Y-m-d", strtotime("today"))?>'],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            fill: {
                opacity: 1
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTApp.getSettings()['font-family']
                },
                y: {
                    formatter: function (val) {
                        return number_format(val) + " VNĐ"
                    }
                }
            },
            colors: [KTApp.getSettings()['colors']['theme']['base']['success'], KTApp.getSettings()['colors']['gray']['gray-300']],
            grid: {
                borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
    }
    var _initChartsWidget2 = function () {
        var element = document.getElementById("kt_charts_widget_2_chart");

        if (!element) {
            return;
        }

        var options = {
            series: [{
                name: 'Tiền Nhận',
                <?php
                    $totalMonth1 = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE `time_tran` >= '".getNgayDauThang($month)."'  AND `time_tran` <= '".getNgayCuoiThang($month)."'  AND status_momo = '999' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalMonth2 = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE `time_tran` >= '".getNgayDauThang(strtotime(date("d/m/Y", strtotime(date("d/m/Y"))) . "-1 months"))."'  AND `time_tran` <= '".getNgayCuoiThang(strtotime(date("d/m/Y", strtotime(date("d/m/Y"))) . "-1 months"))."'  AND status_momo = '999' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalMonth3 = $db->fetch_row("SELECT SUM(amount) FROM momo_history WHERE `time_tran` >= '".getNgayDauThang(strtotime(date("d/m/Y", strtotime(date("d/m/Y"))) . "-2 months"))."'  AND `time_tran` <= '".getNgayCuoiThang(strtotime(date("d/m/Y", strtotime(date("d/m/Y"))) . "-2 months"))."'  AND status_momo = '999' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    
                ?>
                data: [<?=$totalMonth3?>, <?=$totalMonth2?> , <?=$totalMonth1?>]
            }, {
                name: 'Tiền Gửi',
                <?php
                    $totalMonth1 = $db->fetch_row("SELECT SUM(amount) FROM send WHERE `time` >= '".getNgayDauThang($month)."'  AND `time` <= '".getNgayCuoiThang($month)."'  AND status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalMonth2 = $db->fetch_row("SELECT SUM(amount) FROM send WHERE `time` >= '".getNgayDauThang(strtotime(date("d/m/Y", strtotime(date("d/m/Y"))) . "-1 months"))."'  AND `time` <= '".getNgayCuoiThang(strtotime(date("d/m/Y", strtotime(date("d/m/Y"))) . "-1 months"))."'  AND status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    $totalMonth3 = $db->fetch_row("SELECT SUM(amount) FROM send WHERE `time` >= '".getNgayDauThang(strtotime(date("d/m/Y", strtotime(date("d/m/Y"))) . "-2 months"))."'  AND `time` <= '".getNgayCuoiThang(strtotime(date("d/m/Y", strtotime(date("d/m/Y"))) . "-2 months"))."'  AND status = 'success' AND `user_id` = '".$accounts['username']."'") ?: 0;
                    ?>
                data: [ <?=($totalMonth3)?>, <?=($totalMonth2)?>, <?=($totalMonth1)?>]
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: ['30%'],
                    endingShape: 'rounded'
                },
            },
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['<?=date("Y-m", strtotime("-2 month"))?>', '<?=date("Y-m", strtotime("-1 month"))?>', '<?=$month?>-<?=$year?>'],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            fill: {
                opacity: 1
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTApp.getSettings()['font-family']
                },
                y: {
                    formatter: function (val) {
                        return  number_format(val) + " VNĐ"
                    }
                }
            },
            colors: [KTApp.getSettings()['colors']['theme']['base']['warning'], KTApp.getSettings()['colors']['gray']['gray-300']],
            grid: {
                borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
    }

    function number_format(num, decimals = ",") {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1' + decimals);
    }
</script>
<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/view/foot.php');
?>
