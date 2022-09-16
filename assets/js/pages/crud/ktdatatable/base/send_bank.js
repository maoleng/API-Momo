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
                source: '/api/momo/send_bank/histories',
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
                sortable: true,
                order: [[ 0, "desc" ]],
                width: 40,
                type: 'number',
                //selector: {
                //    class: ''
                //},
                textAlign: 'center',
            }, {
                field: 'Phone',
                title: 'Số Tài Khoản',
                width: 60,
                
            },{
                field: 'Name',
                title: 'Người Nhận',
                
            }, {
                field: 'tranId',
                title: 'TranID',
                template: function(row) {
                    return 'MomoID: ' + row.momo_id + '</br> Mã Giao Dịch: ' + row.tranId;
                },
            },{
                field: 'Amount',
                title: 'Số Tiền',
                
            },{
                field: 'Comment',
                title: 'Nội Dung',
                
            }, {
                field: 'Date',
                title: 'Thời Gian',
            }, {
                field: 'Status',
                title: 'Status',
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
                        }
                    };
                    return '<span class="label font-weight-bold label-lg' + status[row.Status].class + ' label-inline">' + status[row.Status].title + '</span>';
                },
            }, {
                field: 'Info',
                title: 'Thông Tin Thêm',
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
