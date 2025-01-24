
<div class="card" id="first_screen">
<div class="d-flex justify-content-between align-items-center pe-4">
<h5 class="card-header">Recived Salary List</h5>
</div>
<div class="card-body">
<div class="table-responsive text-nowrap">
<table class="table table-bordered" id="recives_salary_table"></table>
</div>
</div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
                    $(document).ready(function() {

                    const recives_salary_table = $('#recives_salary_table').DataTable({
                    ordering: false,
                    processing: true,
                    order: [],
                    language: {
                        search: '<span>Filter:</span> _INPUT_',
                        searchPlaceholder: 'Type to filter...',
                        lengthMenu: '<span>Show:</span> _MENU_',
                        "loadingRecords": "",
                        paginate: {
                            'first': 'First',
                            'last': 'Last',
                            'next': '&rarr;',
                            'previous': '&larr;'
                        }
                    },
                    "ajax": {
                        url: "<?= base_url() ?>Teachers/recived_salary_list",
                        type: "POST",
                        dataSrc: function(json) {
                            if (json.Resp_code == 'RLD') {
                                window.location.reload(true);
                            } else if (json.Resp_code != 'RCS') {
                                toastr.error(json.Resp_desc)
                            }
                            return json.data ? json.data : [];
                        },

                        
                    },
                    columns: [
                        {
                            title: 'Salary Recived Date',
                            data: 'DATE(paid_date)',
                            class: 'compact all',
                        }, 
                        {
                            title: 'Salary Amount',
                            data: 'salary_amount',
                            class: 'compact all',
                        }, 
                    ],
                    buttons: [{
                            extend: 'csv',
                            className: 'btn btn-info ml-2',
                            title: 'Student Details',
                            exportOptions: {
                                columns: ":not(.ignoreexport)"
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            className: 'btn btn-info ml-2',
                            title: 'Student Details',
                            exportOptions: {
                                columns: ":not(.ignoreexport)"
                            }

                        },
                        {
                            extend: 'pdfHtml5',
                            className: 'btn btn-info ml-2',
                            title: 'Student Details',
                            exportOptions: {
                                columns: ":not(.ignoreexport)"
                            },
                            orientation: 'landscape',
                            pageSize: 'LEGAL'

                        },

                    ]
                        })

                    })
</script>