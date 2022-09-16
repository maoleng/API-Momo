<?php
   require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/init.php');
   require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/view/head.php');
   if(!is_client()){
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
	            <code>Nếu không hiểu</code> vui lòng inbox <a href="//dtsr11.com">ADMIN</a> để được hỗ trợ tốt nhất!
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
	              <a class="nav-link active" data-toggle="tab" href="#MomoSettings">Momo Settings</a>
	            </li>
	          </ul>
	        </div>
	        <!--end::Header-->
	        <!--begin::Form-->
	        <form class="form">
	          <div class="card-body">
	            <div class="form-group row ">
	              <label class="col-form-label col-lg-3">Momo: <span class="text-danger">*</span>
	              </label>
	              <div class="col-lg-9">
	                <input type="text" name="momo" id="momo" class="form-control is-valid" />
	                <div class="valid-feedback">Vui lòng nhập số điện thoại MoMo.</div>
	              </div>
	            </div>
	            <div class="form-group row ">
	              <label class="col-form-label col-lg-3">OTP: <span class="text-danger">*</span>
	              </label>
	              <div class="col-lg-9">
	                <div class="input-group">
	                  <input type="number" name="otp" id="otp" placeholder="Ấn cái nút nhận otp,đợi xong hãng nhập nha baby" class="form-control is-valid is-invalid">
	                  <span class="input-group-btn">
	                    <input class="btn btn-secondary" id="send" type='button' onclick='myFunction(document.getElementById("momo").value)' value='GET OTP'>
	                  </span>
	                  <div class="invalid-feedback">Vui lòng ấn nút gửi mã để nhận otp từ MoMo.</div>
	                  <div class="valid-feedback">Sau đó nhập mã vào ô trên.</div>
	                </div>
	              </div>
	            </div>
	            <div class="form-group row ">
	              <label class="col-form-label col-lg-3">Mật Khẩu: <span class="text-danger">*</span>
	              </label>
	              <div class="col-lg-9">
	                <input type="text" name="password" id="password" class="form-control is-valid" />
	                <div class="valid-feedback">Vui lòng nhập mật khẩu MoMo.</div>
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
	<!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid" id="list">
	  <!--begin::Entry-->
	  <div class="d-flex flex-column-fluid">
	    <!--begin::Container-->
	    <div class="container">
	      <!--begin::Card-->
	      <div class="card card-custom">
	        <div class="card-header flex-wrap border-0 pt-6 pb-0">
	          <div class="card-title">
	            <h3 class="card-label">Danh Sách Momo </h3>
	          </div>
	          <div class="card-toolbar"></div>
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
	                      <input type="text" class="form-control" placeholder="Search..." id="kt_datatable_search_query" />
	                      <span>
	                        <i class="flaticon2-search-1 text-muted"></i>
	                      </span>
	                    </div>
	                  </div>
	                  <div class="col-md-4 my-2 my-md-0">
	                    <div class="d-flex align-items-center">
	                      <label class="mr-3 mb-0 d-none d-md-block">Status:</label>
	                      <select class="form-control" id="kt_datatable_search_status">
	                        <option value="">All</option>
	                        <option value="success">Đang Chạy</option>
	                        <option value="stop">Ngưng Chạy</option>
	                        <option value="error">Lỗi</option>
	                        <option value="pending">Chờ</option>
	                      </select>
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
	          <div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable"></div>
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
	<script src="/assets/js/pages/crud/ktdatatable/base/momo_account.js?v=
				<?=rand(1,99993219)?>">
	</script>
	<!--end::Page Scripts-->
	<script>
  function del(id) {
    $.ajax({
      type: "POST",
      url: "/api/momo/delete",
      data: {
        id: id
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
  }
  
  
</script>
<script>
function reload(phone) {
    $.ajax({
      type: "POST",
      url: "/api/momo/reload",
      data: {
        phone: "0" + phone,
        user_id : "<?=$accounts['username']?>",
        api_key : "<?=$accounts['api_key']?>"
      },
      success: function(result1) {
        document.getElementById("submit").disabled = false;
        result = JSON.parse(result1);
        if (result.status == "success") {
          swal.fire({
            text: result.message,
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn font-weight-bold btn-light-primary"
            }
          });
        } else {
          swal.fire({
            text: result.message,
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
  }
  </script>
	<script>
	  $(document).on("keypress", function(e) {
	    if (e.which == 13) {
	      $("#submit").click();
	    }
	  });
	  $(document).ready(function() {
	    $("#submit").click(function() {
	      document.getElementById("submit").disabled = true;
	      momo = $("#momo").val();
	      otp = $("#otp").val();
	      password = $("#password").val();
	      $.ajax({
	        type: "POST",
	        url: "/api/momo/confirm",
	        data: {
	          password: password,
	          otp: otp,
	          momo: momo
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
	<script>
	  function myFunction(momo) {
	    document.getElementById("send").disabled = true;
	    document.getElementById("send").innerHTML = "Proceed To Send OTP";
	    $.ajax({
	      type: "POST",
	      url: "/api/momo/sendotp",
	      data: {
	        momo: momo
	      },
	      success: function(result) {
	        document.getElementById("send").disabled = false;
	        document.getElementById("send").innerHTML = "GET OTP";
	        result = JSON.parse(result);
	        if (result.status == "success") {
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
	          });
	        }
	      },
	    });
	  }
	</script>
<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/view/foot.php');
?>