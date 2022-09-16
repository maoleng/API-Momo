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
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Gửi Tiền Đến Ngân Hàng</h5>
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
            <!--begin::Notice-->
            <div class="alert alert-custom alert-white alert-shadow gutter-b" role="alert">
                <div class="alert-icon alert-icon-top"> <span class="svg-icon svg-icon-3x svg-icon-primary mt-4">
                  <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Tools/Tools.svg-->
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <path d="M15.9497475,3.80761184 L13.0246125,6.73274681 C12.2435639,7.51379539 12.2435639,8.78012535 13.0246125,9.56117394 L14.4388261,10.9753875 C15.2198746,11.7564361 16.4862046,11.7564361 17.2672532,10.9753875 L20.1923882,8.05025253 C20.7341101,10.0447871 20.2295941,12.2556873 18.674559,13.8107223 C16.8453326,15.6399488 14.1085592,16.0155296 11.8839934,14.9444337 L6.75735931,20.0710678 C5.97631073,20.8521164 4.70998077,20.8521164 3.92893219,20.0710678 C3.1478836,19.2900192 3.1478836,18.0236893 3.92893219,17.2426407 L9.05556629,12.1160066 C7.98447038,9.89144078 8.36005124,7.15466739 10.1892777,5.32544095 C11.7443127,3.77040588 13.9552129,3.26588995 15.9497475,3.80761184 Z" fill="#000000"></path>
                        <path d="M16.6568542,5.92893219 L18.0710678,7.34314575 C18.4615921,7.73367004 18.4615921,8.36683502 18.0710678,8.75735931 L16.6913928,10.1370344 C16.3008685,10.5275587 15.6677035,10.5275587 15.2771792,10.1370344 L13.8629656,8.7228208 C13.4724413,8.33229651 13.4724413,7.69913153 13.8629656,7.30860724 L15.2426407,5.92893219 C15.633165,5.5384079 16.26633,5.5384079 16.6568542,5.92893219 Z" fill="#000000" opacity="0.3"></path>
                     </g>
                  </svg>
                  <!--end::Svg Icon-->
               </span> </div>
                <div class="alert-text">
                    <p> <code>Nếu không hiểu</code> vui lòng inbox <a href="//fgocheat.net">ADMIN</a> để được hỗ trợ tốt nhất! </p>
                    <p> <span class="label label-inline label-pill label-danger label-rounded mr-2">NOTE:</span> Bạn vui lòng điền đủ các thông tin để API chạy tốt nhất! </p>
                </div>
            </div>
            <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header card-header-tabs-line">
                    <ul class="nav nav-dark nav-bold nav-tabs nav-tabs-line" data-remember-tab="tab_id" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#MomoSend">Gửi Tiền Đến Ngân Hàng</a> </li>
                    </ul>
                </div>
                <!--end::Header-->
                <!--begin::Form-->
                <form class="form" id="sendForm">
                    <div class="card-body">
                        <div class="form-group row ">
                            <label class="col-form-label col-lg-3">Momo Gửi: <span class="text-danger">*</span> </label>
                            <div class="col-lg-9">
									<select class="form-control is-valid" name="from" id="from">
										<?php 
                              $getdl = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `user_id` = '".$accounts['username']."'",0);
                              foreach($getdl as $value){
                              ?>
											<option value="<?=$value['phone']?>"><?=$value['phone']?> - <?=number_format($value['BALANCE'])?>đ </option>
											<?php } ?>
									</select>
                                <div class="valid-feedback">Vui lòng chọn số điện thoại MoMo cần gửi tiền.</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-form-label col-lg-3">Ngân hàng nhận: <span class="text-danger">*</span> </label>
                            <div class="col-lg-9">
                                <select class="form-control is-valid" name="bankcode" id="bankcode">
                                    <option value="0"> - Chọn Ngân Hàng - </option>
                                    <option value="970455">NH Cong Nghiep Han Quoc CN Ha Noi (IBK HN)</option>
                                    <option value="970425">NH TMCP An Binh (ABBank)</option>
                                    <option value="970427">NH TMCP Viet A (VietABank)</option>
                                    <option value="970436">NH TMCP Ngoai Thuong VN (VietcomBank)</option>
                                    <option value="970415">NH TMCP Cong Thuong VN (Vietinbank)</option>
                                    <option value="970407">NH TMCP Ky Thuong VN (Techcombank)</option>
                                    <option value="970418">NH TMCP Dau Tu va Phat Trien VN (BIDV)</option>
                                    <option value="970405">NH Nông nghiệp và Phát triển Nông thôn</option>
                                    <option value="970403">NH TMCP Sai Gon Thuong Tin (Sacombank)</option>
                                    <option value="970416">NH TMCP A Chau (ACB)</option>
                                    <option value="970422">NH TMCP Quan Doi (MB)</option>
                                    <option value="970423">NH TMCP Tien Phong (TPBank)</option>
                                    <option value="970424">NH TNHH MTV Shinhan VN (ShinhanBank)</option>
                                    <option value="970441">NH TMCP Quoc Te VN (VIB)</option>
                                    <option value="970443">NH TMCP Sai Gon Ha Noi (SHB)</option>
                                    <option value="970431">NH TMCP Xuat Nhap khau VN (Eximbank)</option>
                                    <option value="970438">NH TMCP Bao Viet (BaoVietBank)</option>
                                    <option value="970429">NH TMCP Sai Gon (SCB)</option>
                                    <option value="970412">NH TMCP Dai Chung VN (PVcombank)</option>
                                    <option value="970414">NH TM TNHH MTV Dai Duong (OceanBank)</option>
                                    <option value="970432">NH TMCP Viet Nam Thinh Vuong (VP Bank)</option>
                                    <option value="970428">NH TMCP Nam A (NamABank)</option>
                                    <option value="970437">NH TMCP Phat Trien TP HCM (HDBank)</option>
                                    <option value="970439">NH TNHH MTV Public VN</option>
                                    <option value="970442">NH TNHH MTV Hongleong VN (HongleongBank)</option>
                                    <option value="970430">NH TMCP Xang Dau Petrolimex (PG Bank)</option>
                                    <option value="970446">NH Hop Tac (Co op Bank)</option>
                                    <option value="970419">NH TMCP Quoc Dan (NCB)</option>
                                    <option value="970434">NH TNHH Indovina (Indovina Bank)</option>
                                    <option value="970408">NH TM TNHH MTV Dau Khi Toan Cau (GPBank)</option>
                                    <option value="970400">NH TMCP Sai Gon Cong Thuong (Saigonbank)</option>
                                    <option value="970426">NH TMCP Hang Hai VN (Maritime Bank)</option>
                                    <option value="970449">NH TMCP Buu Dien Lien Viet (LienVietPostBank)</option>
                                    <option value="970452">NH TMCP Kien Long (KienLongBank)</option>
                                    <option value="970457">NH Wooribank</option>
                                    <option value="970440">NH TMCP Dong Nam A(SeABank)</option>
                                    <option value="970458">NH TNHH MTV United Overseas Bank (UOB)</option>
                                    <option value="970410">Standard Chartered</option>
                                    <option value="970448">NH TMCP Phuong Dong (OCB)</option>
                                    <option value="422589">NH TNHH MTV CIMB Viet Nam (CIMB)</option>
                                    <option value="970454">NH TMCP Ban Viet (Viet Capital Bank)</option>
                                    <option value="970406">NH TMCP Dong A (DongA Bank)</option>
                                    <option value="970421">NH Lien Doanh Viet Nga (VRB)</option>
                                    <option value="970409">NH TMCP Bac A (BacABank)</option>
                                    <option value="970433">NH TMCP Viet Nam Thuong Tin (VietBank)</option>
                                </select>
                                <div class="valid-feedback">Chọn ngân hàng gửi đến.</div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-form-label col-lg-3">Số tài khoản nhận: <span class="text-danger">*</span> </label>
                            <div class="col-lg-9">
                                <input type="text" name="account_number" id="account_number" class="form-control is-valid">
                                <div class="valid-feedback">Vui lòng nhập số tài khoản cần gửi tiền đến.</div>
                            </div>
                        </div>
                        <div class="form-group row" id="name">
                            <label class="col-form-label col-lg-3">Tên Người Nhận: <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <input type="text" name="name" id="name" value="" class="form-control" disabled="">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-form-label col-lg-3">Số tiền: <span class="text-danger">*</span> </label>
                            <div class="col-lg-9">
                                <input type="number" name="amount" id="amount" class="form-control is-valid">
                                <div class="valid-feedback">Vui lòng nhập số tiền cần gửi &gt;= 20.000.</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-form-label col-lg-3">Lời Nhắn: <span class="text-danger">*</span> </label>
                            <div class="col-lg-9">
                                <input type="text" name="message" id="message" class="form-control is-valid"> </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-9 ml-lg-auto">
                                    <button type="submit" id="submit" name="submit" class="btn btn-primary font-weight-bold mr-2">Gửi</button>
                                </div>
                            </div>
                        </div>

                        <!--end::Form-->
                    </div>
                </form>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_his">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">Gửi Tiền Đến Ngân Hàng</h5>
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

                <!--begin::Card-->
                <div class="card card-custom">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">Lịch Sử Giao Dịch
											</h3>
                        </div>
                        <div class="card-toolbar">


                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Search Form-->
                        <!--begin::Search Form-->
                        <div class="mb-7">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-xl-8">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 my-2 my-md-0">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Search..." id="kt_datatable_search_query">
                                                <span>
																	<i class="flaticon2-search-1 text-muted"></i>
																</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2 my-md-0">
                                            <div class="d-flex align-items-center">
                                                <label class="mr-3 mb-0 d-none d-md-block">Status:</label>
                                                <div class="dropdown bootstrap-select form-control">
                                                    <select class="form-control" id="kt_datatable_search_status">
                                                        <option value="">All</option>
                                                        <option value="success">Thành Công</option>
                                                        <option value="error">Thất Bại</option>
                                                    </select>
                                                    <button type="button" tabindex="-1" class="btn dropdown-toggle btn-light bs-placeholder" data-toggle="dropdown" role="combobox" aria-owns="bs-select-1" aria-haspopup="listbox" aria-expanded="false" data-id="kt_datatable_search_status" title="All">
                                                        <div class="filter-option">
                                                            <div class="filter-option-inner">
                                                                <div class="filter-option-inner-inner">All</div>
                                                            </div>
                                                        </div>
                                                    </button>
                                                    <div class="dropdown-menu ">
                                                        <div class="inner show" role="listbox" id="bs-select-1" tabindex="-1">
                                                            <ul class="dropdown-menu inner show" role="presentation"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                                    <a href="#kt_his" class="btn btn-light-primary px-6 font-weight-bold">Search</a>
                                </div>
                            </div>
                        </div>
                        <!--end::Search Form-->
                        <!--end: Search Form-->
                        <!--begin: Datatable-->
                        <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-error datatable-loaded datatable-scroll" id="kt_datatable" style="position: static; zoom: 1;">
                            <table class="datatable-table" style="display: block;">
                                <thead class="datatable-head">
                                    <tr class="datatable-row" style="left: 0px;">
                                        <th data-field="RecordID" class="datatable-cell-center datatable-cell datatable-cell-sort"><span style="width: 40px;">#</span>
                                        </th>
                                        <th data-field="Phone" class="datatable-cell datatable-cell-sort"><span style="width: 60px;">Số Tài Khoản</span>
                                        </th>
                                        <th data-field="Name" class="datatable-cell datatable-cell-sort"><span style="width: 108px;">Người Nhận</span>
                                        </th>
                                        <th data-field="tranId" class="datatable-cell datatable-cell-sort"><span style="width: 108px;">TranID</span>
                                        </th>
                                        <th data-field="Amount" class="datatable-cell datatable-cell-sort"><span style="width: 108px;">Số Tiền</span>
                                        </th>
                                        <th data-field="Comment" class="datatable-cell datatable-cell-sort"><span style="width: 108px;">Nội Dung</span>
                                        </th>
                                        <th data-field="Date" class="datatable-cell datatable-cell-sort"><span style="width: 108px;">Thời Gian</span>
                                        </th>
                                        <th data-field="Status" class="datatable-cell datatable-cell-sort"><span style="width: 108px;">Status</span>
                                        </th>
                                        <th data-field="Info" class="datatable-cell datatable-cell-sort"><span style="width: 108px;">Thông Tin Thêm</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="datatable-body" style=""><span class="datatable-error">No records found</span>
                                </tbody>
                            </table>
                        </div>
                        <!--end: Datatable-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->
</div>
	<!--begin::Page Scripts(used by this page)-->
		<script src="/assets/js/pages/crud/ktdatatable/base/send.js?v=<?=rand()?>"></script>
	<!--end::Page Scripts-->
	<script>
	$(document).on("keypress", function(e) {
		if(e.which == 13) {
			$("#submit").click();
		}
	});
	$(document).ready(function() {
		$("#account_number , #from , #bankcode").change(function() {
			from = $("#from option:selected").val();
			account_number = $("#account_number").val();
			bankcode = $("#bankcode").val();
			if(account_number && bankcode) {
				$.ajax({
					url: "/api/momo/checkname",
					method: "post",
					data: {
						from: from,
						account_number: account_number,
						bankcode : bankcode
					},
					
					success: function(data) {
						obj = JSON.parse(data);
						if(obj.name == "" || obj.name == "Không tìm thấy" || !obj.name) {
							var name1 = '<div class="invalid-feedback">Không tìm thấy tài khoản người nhận.</div>';
							var status = "invalid";
						} else {
							var name1 = '';
							var status = "valid";
						}
						var extra = '<label class="col-form-label col-lg-3">Tên Người Nhận: <span class="text-danger">*</span></label>' + '<div class="col-lg-9">' + '<input type="text" name="name" id="name" value="' + obj.name + '" class="form-control is-' + status + '" disabled/>' + name1 + '</div>';
						$("#name").html(extra);
					}
				});
			}
		});
		$("#submit").click(function() {
			document.getElementById("submit").disabled = true;
			from = $("#from option:selected").val();
			account_number = $("#account_number").val();
			amount = $("#amount").val();
			message = $("#message").val();
			bankcode = $("#bankcode").val();
			$.ajax({
				type: "POST",
				url: "/api/momo/send_bank",
				data: {
					from: from,
					message: message,
					account_number: account_number,
					amount: amount,
					bankcode : bankcode
				},
				success: function(result1) {
					document.getElementById("submit").disabled = false;
					result = JSON.parse(result1);
					if(result.status == "success") {
						swal.fire({
							text: result.msg,
							icon: "success",
							buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn font-weight-bold btn-light-primary"
							}
						}).then(function() {
	                        KTUtil.scrollTop();
	                        window.location.href = "";
	                    });
					} else {
						swal.fire({
							text: result.msg,
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn font-weight-bold btn-light-primary"
							}
						}).then(function() {
	                        KTUtil.scrollTop();
	                        window.location.href = "";
	                    });
					}
				}
			});
		});
	});
	</script>
<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/view/foot.php');
?>