
<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Manage Pending Teacher Salary</h5>
       
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="teacher_salary_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {

        const teacher_salary_table = $('#teacher_salary_table').DataTable({
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
                url: "<?= base_url() ?>Teachers/get_pending_salary_list",
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
            columns: [{
                    title: 'Teacher Name',
                    data: 'name',
                    class: 'compact all',
                },
                {
                    title: 'Email',
                    data: 'email',
                    class: 'compact all',
                },
                {
                    title: 'Salary Due Amount',
                    data: 'salary',
                    class: 'compact all',
                },
                {
                    title: 'Salary Due Date',
                    data: 'due_date',
                    class: 'compact all',
                }, 
               
                {
                    title: 'Action',
                    data: null,
                    class: 'compact all',
                    render: function(data, type, full, meta) {
                        return `
                            <div class="d-flex">
                                <a class="dropdown-item pay_pending_salary btn-success" style="width:max-content;" href="javascript:void(0);">Pay Now</a>
                            </div>
                        `;
                    }
                }
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

        /* ------------------------------ Edit Student Form ----------------------------- */
        teacher_salary_table.on('click', '.pay_pending_salary', function() {
            const row = $(this).closest('tr');
            const showtd = teacher_salary_table.row(row).data();
           
            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Student</h5>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-6">
                        <label class="form-label" for="student_name">Student Name</label>
                        <input type="text" class="form-control" id="student_name" value="${showtd.name}" autofocus readonly>
                    </div>
                      <div class="col-6 mb-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="${showtd.email}" autofocus readonly>
                    </div>
                </div>

                <div class="row">
                      <div class="col-6 mb-6">
                        <label class="form-label" for="salary_amount">Salary Amount</label>
                        <input type="number" class="form-control" id="salary_amount" value="${showtd.salary}" autofocus readonly>
                    </div>
                    <div class="col-6 mb-6">
                        <label class="form-label" for="bank_name">Bank Name</label>
                        <input type="text" class="form-control" id="bank_name" value="${showtd.bank_name}" autofocus readonly>
                    </div>
                </div>

                <div class="row">
                      <div class="col-6 mb-6">
                        <label class="form-label" for="account_holder_name">Account Holder Name</label>
                        <input type="text" class="form-control" id="account_holder_name" value="${showtd.account_holder_name}" autofocus readonly>
                    </div>
                    <div class="col-6 mb-6">
                        <label class="form-label" for="ifsc_code">IFSC Code</label>
                        <input type="text" class="form-control" id="ifsc_code" value="${showtd.ifsc_code}" autofocus readonly>
                    </div>
                </div>

                 <div class="row">
                      <div class="col-6 mb-6">
                        <label class="form-label" for="account_number">Account Number</label>
                        <input type="number" class="form-control" id="account_number" value="${showtd.account_number}" autofocus readonly>
                    </div>
                </div>
                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="pay_pending_salary">Proceed to Pay</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            /* ---------------------------- Save Edited Student Data --------------------------- */

            $('#pay_pending_salary').click(function() {
                const params = {
                    valid: true,
                    student_id: showtd.student_id,
                    student_name: $('#student_name').val(),
                    email: $('#email').val(),
                    salary_amount: $('#salary_amount').val(),
                   
                }

                $.ajax({
                    url: '<?= base_url() ?>Fees/pay_pending_salary',
                    method: 'POST',
                    dataType: 'JSON',
                    data: params,
                    success: function(res) {
                        if (res.Resp_code === 'RCS') {
                            toastr.info(res.Resp_desc)
                            $('#back_to_first_screen').click()
                            teacher_salary_table.ajax.reload()
                        } else if (res.Resp_code === 'RLD') {
                            window.location.reload();
                        } else {
                            toastr.error(res.Resp_desc)
                        }
                    }
                })
            })




        })



    })
</script>