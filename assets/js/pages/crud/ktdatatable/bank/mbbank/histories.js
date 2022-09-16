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
                source: '/api/bank/mbbank/histories',
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
                textAlign: 'center',
            }, {
                field: 'accountNumber',
                title: 'STK',
                
            },{
                field: 'CD',
                title: 'Loại',
                textAlign: 'center',
                // callback function support for column rendering
                template: function(row) {
                    var status = {
                        '+' : {
                            'title': 'Cộng Tiền',
                            'class': ' label-light-success'
                        },
                        '-' : {
                            'title': 'Trừ Tiền',
                            'class': ' label-light-danger'
                        }
                        
                    };
                    return '<span class="label font-weight-bold label-lg' + status[row.CD].class + ' label-inline">' + status[row.CD].title + '</span>';
                },
            },{
                field: 'refNo',
                title: 'Mã GD',
                
            },{
                field: 'amount',
                title: 'Số Tiền',
                
            },{
                field: 'content',
                title: 'Nội Dung',
                
            },{
                field: 'availableBalance',
                title: 'Số Dư',
                
            }, {
                field: 'transactionDate',
                title: 'Thời Gian',
            }, {
                field: 'Info',
                title: 'Thông Tin Thêm',
                template: function(row) {
                    return 'Tên: ' + row.benAccountName + '</br> STK: ' + row.benAccountNo + '</br> NH: ' + row.bankName;
                },
            }],

        });

        $('#kt_datatable_search_status').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'CD');
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
