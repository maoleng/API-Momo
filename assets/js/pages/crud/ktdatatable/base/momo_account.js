"use strict";
// Class definition
var KTDatatableJsonRemoteDemo = function() {
	// Private functions
	// basic demo
	var demo = function() {
		var datatable = $('#kt_datatable').KTDatatable({
			// datasource definition
			data: {
				type: 'remote',
				source: '/api/momo/account/list',
				pageSize: 10,
			},
			// layout definition
			layout: {
				scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
				footer: false // display/hide footer
			},
			// column sorting
			sortable: true,
			pagination: true,
			search: {
				input: $('#kt_datatable_search_query'),
				key: 'generalSearch'
			},
			// columns definition
			columns: [{
				field: 'RecordID',
				title: '#',
				sortable: false,
				width: 20,
				type: 'number',
				selector: {
					class: ''
				},
				textAlign: 'center',
			}, {
				field: 'phone',
				title: 'Phone',
			}, {
				field: 'Name',
				title: 'Tên Ví',
			}, {
				field: 'BALANCE',
				title: 'Số Dư'
			}, {
				field: 'today',
				title: 'Giao Dịch',
				template: function(row) {
					return 'Ngày: ' + row.today + ' | ' + row.today_gd + ' </br> Tháng: ' + row.month;
				}
			}, {
				field: 'Date',
				title: 'Thời Gian',
			}, {
				field: 'Status',
				title: 'Trạng Thái',
				// callback function support for column rendering
				template: function(row) {
					var status = {
						'success': {
							'title': 'Thành Công',
							'class': ' label-light-success'
						},
						'error': {
							'title': 'Thất Bại',
							'class': ' label-light-danger'
						},
						'pending': {
							'title': 'Đang Chờ',
							'class': ' label-light-warning'
						},
						'stop': {
							'title': 'Ngưng Chạy',
							'class': ' label-light-primary'
						}
					};
					return '<span class="label font-weight-bold label-lg' + status[row.Status].class + ' label-inline">' + status[row.Status].title + '</span>';
				},
			}, {
				field: 'errorDesc',
				title: 'Thông Tin Thêm',
			}, {
				field: 'Actions',
				title: 'Actions',
				sortable: false,
				width: 125,
				autoHide: false,
				overflow: 'visible',
				template: function(row) {
				    
					return '\
                        <div class="dropdown dropdown-inline">\
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
                                <span class="svg-icon svg-icon-md">\
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                            <rect x="0" y="0" width="24" height="24"/>\
                                            <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>\
                                        </g>\
                                    </svg>\
                                </span>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
                                <ul class="navi flex-column navi-hover py-2">\
                                    <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
                                        Chọn Hành Động:\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="/settings/momo_account/' + row.RecordID + '" class="ls-modal navi-link">\
                                            <span class="navi-icon"><i class="la la-edit"></i></span>\
                                            <span class="navi-text">Edit</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="#" onclick="del(' + row.RecordID + ');" class="navi-link">\
                                            <span class="navi-icon"><i class="la la-crop"></i></span>\
                                            <span class="navi-text">Xoá</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="#" onclick="reload('+row.phone+');" class="navi-link">\
                                            <span class="navi-icon"><i class="la la-upload"></i></span>\
                                            <span class="navi-text">Reload</span>\
                                        </a>\
                                    </li>\
                                </ul>\
                            </div>\
                        </div>\
                    ';
				},
			}],
		});
		$('#kt_datatable_search_status').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'Status');
		});
		$('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
	};
	return {
		// public functions
		init: function() {
			demo();
		}
	};
}();
jQuery(document).ready(function() {
	KTDatatableJsonRemoteDemo.init();
});