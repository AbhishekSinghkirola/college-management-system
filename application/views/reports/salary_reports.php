<?php $teacher_list = get_teacher_list();?>

<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Salary Reports</h5>
    </div>

    <div class="card-body">
                <div class="row">
                <div class="col-4">
                        <label class="form-label" for="teacher_name">Teachers</label>
                        <select class="form-control" id="teacher_name">
                            <option value="" selected disabled>Select Teacher</option>
                            
                            <?php foreach($teacher_list as $teacher) { ?>

                                <option value="<?= $teacher['teacher_id'] ?>"><?=  $teacher['name'] ?></option>

                           <?php }?>
                        </select>
                    </div>
                   

                <div class="col-3">
                    <label for="bs-rangepicker-basic" class="form-label">From Date</label>
                    <input type="date" id="from_date" class="form-control">
                </div>
                <div class="col-3">
                    <label for="bs-rangepicker-basic" class="form-label">To Date</label>
                    <input type="date" id="to_date" class="form-control">
                </div>
                <div class="col-2 mt-1">
                    <input type="submit" id="submit" name="submit" Value="Search" class="btn btn-success mt-4">
                </div>

                </div>

    </div>


    
            <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="salary_reports_table"></table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
      
        const salary_reports_table = $('#salary_reports_table').DataTable({
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
                url: "<?= base_url() ?>Reports/get_salary_list",
                type: "POST",

                data: function(d) {
                    // Add filter values to the request payload
                    d.teacher_name = $('#teacher_name').val(); // Filter by teacher name
                    d.from_date = $('#from_date').val(); // Filter by from date
                    d.to_date = $('#to_date').val(); // Filter by to date
                },
                dataSrc: function(json) {
                    if (json.Resp_code == 'RLD') {
                        window.location.reload(true);
                    } else if (json.Resp_code != 'RCS') {
                        toastr.error(json.Resp_desc);
                    }
                    return json.data ? json.data : [];
                }

                
            },
            columns: [{
                    title: 'Teacher Name',
                    data: 'name',
                    class: 'compact all',
                },
                {
                    title: 'Teacher Email',
                    data: 'email',
                    class: 'compact all',
                },
                {
                    title: 'Salary Amount',
                    data: 'salary_amount',
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

        $('#submit').click(function () {
            salary_reports_table.ajax.reload(); // Reload table with updated filters
    });


    })
</script>