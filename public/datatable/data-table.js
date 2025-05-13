
(function ($) {
    'use strict';
    $(function () {
        $('#order-listing').DataTable({
            "dom": '<"top-buttons"<"center-col mb-2 d-flex flex-md-row w-100  align-itmes-center justify-content-between mt-2 mt-md-0 mb-md-0"lBf><"right-col">>rtip',

            scrollX: true,
            autoWidth: true,
            "LengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            "iDisplayLength": 10,
            "language": {
                search: ""
            },
            buttons: [{
                extend: "excel",
                className: "btn-sm export-btn mb-2 mb-md-0",
                titleAttr: 'Export in Excel',
                text: 'Excel',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },
            {
                extend: "copy",
                className: "btn-sm export-btn mb-2 mb-md-0",
                titleAttr: 'Copy to Clipboard',
                text: 'Copy',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },
            {
                extend: "csv",
                className: "btn-sm export-btn mb-2 mb-md-0",
                titleAttr: 'Export to CSV',
                text: 'CSV',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            },

            {
                extend: "pdf",
                className: "btn-sm export-btn mb-2 mb-md-0",
                titleAttr: 'Export to pdf',
                text: 'PDF',
                init: function (api, node, config) {
                    $(node).removeClass('dt-button')
                }
            }
            ]
        });
        $('#order-listing').each(function () {
            var datatable = $(this);
            // SEARCH - Add the placeholder for Search and Turn this into in-line form control
            var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
            search_input.attr('placeholder', 'Search');
            search_input.removeClass('form-control-sm');
            // LENGTH - Inline-Form control
            var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
            length_sel.removeClass('form-control-sm');
        });
    });
})(jQuery);