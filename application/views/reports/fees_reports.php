<?php $fees = get_fees_list();?>

<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Fees Reports</h5>
    </div>
            <div class="card-body">
                <div class="row">
                <div class="col-6">
                        <label class="form-label" for="course_name">Students</label>
                        <select class="form-control" id="course_name">
                            <option value="">Select Student</option>
                            
                            <?php foreach($fees as $student) { ?>

                                <option value="<?= $student['student_id'] ?>"><?=  $student['student_name'] ?></option>

                           <?php }?>
                        </select>
                    </div>
                   
                </div>

            </div>




    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="fees_reports_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {
      
        const fees_reports_table = $('#fees_reports_table').DataTable({
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
                url: "<?= base_url() ?>Reports/get_fees_list",
                type: "POST",
                dataSrc: function(json) {
                    console.log();
                    if (json.Resp_code == 'RLD') {
                        window.location.reload(true);
                    } else if (json.Resp_code != 'RCS') {
                        toastr.error(json.Resp_desc)
                    }
                    return json.data ? json.data : [];
                },
            },
            columns: [{
                    title: 'Student Name',
                    data: 'student_name',
                    class: 'compact all',
                },
                {
                    title: 'Email',
                    data: 'email',
                    class: 'compact all',
                },
                {
                    title: 'Fees Amount',
                    data: 'fees_amount',
                    class: 'compact all',
                },
                {
                    title: 'Paid Date',
                    data: 'paid_date',
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

        });






    })
</script>