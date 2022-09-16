<?php 
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/init.php');
   require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/view/head.php');
   if(!is_client()){
       load_url('/quanly');
   }
   
$id = junoo_boc(GET('id'));
$result = $db->fetch_assoc("SELECT * FROM `cron_momo` WHERE `id` = '".$id."' AND `user_id` = '".$accounts['username']."' ORDER BY `id` DESC LIMIT 1",1);
if(!$result['id']) {
   load_url('/quanly'); 
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
          <h5 class="text-dark font-weight-bold my-1 mr-5">Momo Settings #<?=$id?> </h5>
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
        <div class="alert-icon alert-icon-top">
          <span class="svg-icon svg-icon-3x svg-icon-primary mt-4">
            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Tools/Tools.svg-->
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24" />
                <path d="M15.9497475,3.80761184 L13.0246125,6.73274681 C12.2435639,7.51379539 12.2435639,8.78012535 13.0246125,9.56117394 L14.4388261,10.9753875 C15.2198746,11.7564361 16.4862046,11.7564361 17.2672532,10.9753875 L20.1923882,8.05025253 C20.7341101,10.0447871 20.2295941,12.2556873 18.674559,13.8107223 C16.8453326,15.6399488 14.1085592,16.0155296 11.8839934,14.9444337 L6.75735931,20.0710678 C5.97631073,20.8521164 4.70998077,20.8521164 3.92893219,20.0710678 C3.1478836,19.2900192 3.1478836,18.0236893 3.92893219,17.2426407 L9.05556629,12.1160066 C7.98447038,9.89144078 8.36005124,7.15466739 10.1892777,5.32544095 C11.7443127,3.77040588 13.9552129,3.26588995 15.9497475,3.80761184 Z" fill="#000000" />
                <path d="M16.6568542,5.92893219 L18.0710678,7.34314575 C18.4615921,7.73367004 18.4615921,8.36683502 18.0710678,8.75735931 L16.6913928,10.1370344 C16.3008685,10.5275587 15.6677035,10.5275587 15.2771792,10.1370344 L13.8629656,8.7228208 C13.4724413,8.33229651 13.4724413,7.69913153 13.8629656,7.30860724 L15.2426407,5.92893219 C15.633165,5.5384079 16.26633,5.5384079 16.6568542,5.92893219 Z" fill="#000000" opacity="0.3" />
              </g>
            </svg>
            <!--end::Svg Icon-->
          </span>
        </div>
        <div class="alert-text">
          <p>
            <code>Nếu không hiểu</code> vui lòng inbox <a href="//fgocheat.net">ADMIN</a> để được hỗ trợ tốt nhất!
          </p>
          <p>
            <span class="label label-inline label-pill label-danger label-rounded mr-2">NOTE:</span> Bạn vui lòng điền đủ các thông tin để API chạy tốt nhất!
          </p>
        </div>
      </div>
      <!--end::Notice-->
      <!--begin::Card-->
      <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header card-header-tabs-line">
          <ul class="nav nav-dark nav-bold nav-tabs nav-tabs-line" data-remember-tab="tab_id" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#MomoSettings">Momo Settings #<?=$id?> </a>
            </li>
          </ul>
        </div>
        <!--end::Header-->
        <!--begin::Form-->
        <form class="form">
          <form id="edit-form" action="" accept-charset="UTF-8" class="form">
            <input type="hidden" name="edit_id" id="edit_id" value="<?=$id?>" class="form-control is-valid" />
            <div class="card-body">
              <div class="form-group row ">
                <label class="col-form-label col-lg-3">Mật Khẩu: <span class="text-danger">*</span>
                </label>
                <div class="col-lg-9">
                  <input type="number" name="edit_password" id="edit_password" value="<?=$result['password']?>" class="form-control is-valid" />
                  <div class="valid-feedback">Mật khẩu MoMo</div>
                </div>
              </div>
              <div class="form-group row ">
                <label class="col-form-label col-lg-3">Hạn mức ngày: <span class="text-danger">*</span>
                </label>
                <div class="col-lg-9">
                  <input type="number" name="edit_limit_day" id="edit_limit_day" value="<?=$result['limit_day']?>" class="form-control is-valid" />
                  <div class="valid-feedback">Hạn mức ngày</div>
                </div>
              </div>
              <div class="form-group row ">
                <label class="col-form-label col-lg-3">Hạn mức tháng: <span class="text-danger">*</span>
                </label>
                <div class="col-lg-9">
                  <input type="number" name="edit_limit_month" id="edit_limit_month" value="<?=$result['limit_month']?>" class="form-control is-valid" />
                  <div class="valid-feedback">Hạn mức tháng</div>
                </div>
              </div>
              <div class="form-group row ">
                <label class="col-form-label col-lg-3">Nội dung: <span class="text-danger">*</span>
                </label>
                <div class="col-lg-9">
                  <input type="text" name="edit_noidungtra" id="edit_noidungtra" value="<?=$result['noidungtra']?>" class="form-control is-valid" />
                  <div class="valid-feedback">Nội dung</div>
                </div>
              </div>
              <div class="form-group row ">
                <label class="col-form-label col-lg-3">Callback: <span class="text-danger">*</span>
                </label>
                <div class="col-lg-9">
                  <input type="text" name="callback_url" id="callback_url" value="<?=$result['callback_url']?>" class="form-control is-valid" />
                  <div class="valid-feedback">Url callback</div>
                </div>
              </div>
              <div class="form-group row ">
                <label class="col-form-label col-lg-3">Trạng Thái: <span class="text-danger">*</span>
                </label>
                <div class="col-lg-9">
                  <select class="form-control is-valid" name="edit_status" id="edit_status">
                    <option value="success" <?php if($result['status'] == 'success'){ ?> selected <?php }?>>Đang Chạy </option>
                    <option value="stop" <?php if($result['status'] == 'stop'){ ?> selected <?php }?>>Ngưng Chạy </option>
                    <option value="error" <?php if($result['status'] == 'error'){ ?> selected <?php }?>>Lỗi </option>
                  </select>
                  <div class="valid-feedback">Trạng thái MoMo</div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-lg-9 ml-lg-auto">
                  <button type="submit" id="submit" name="submit" class="btn btn-primary font-weight-bold mr-2">Submit</button>
                </div>
              </div>
            </div>
          </form>
          <!--end::Form-->
      </div>
      <!--end::Card-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::Entry-->
</div>
<!--end::Content-->
<script>
  $(document).on("keypress", function(e) {
    if (e.which == 13) {
      $("#submit").click();
    }
  });
  $(document).ready(function() {
    $("#submit").click(function() {
      document.getElementById("submit").disabled = true;
      edit_id = $("#edit_id").val();
      edit_password = $("#edit_password").val();
      edit_limit_day = $("#edit_limit_day").val();
      edit_limit_month = $("#edit_limit_month").val();
      edit_noidungtra = $("#edit_noidungtra").val();
      callback_url = $("#callback_url").val();
      edit_status = $("#edit_status option:selected").val();
      $.ajax({
        type: "POST",
        url: "/api/momo/settings",
        data: {
          edit_id: edit_id,
          edit_password: edit_password,
          edit_limit_day: edit_limit_day,
          edit_limit_month: edit_limit_month,
          edit_noidungtra: edit_noidungtra,
          callback_url : callback_url,
          edit_status: edit_status
        },
        success: function(result1) {
          document.getElementById("submit").disabled = false;
          result = JSON.parse(result1);
          if (result.status == "success") {
            swal.fire({
              text: result.msg,
              icon: "success",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn font-weight-bold btn-light-primary"
              }
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
            });
          }
        },
      });
    });
  });
</script>
<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/view/foot.php');
?>