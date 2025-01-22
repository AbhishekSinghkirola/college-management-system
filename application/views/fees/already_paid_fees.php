 <!-- alert for pending fees start-->
 <?php  if($fees_status == 'pending'): ?>

            <div class="alert alert-danger alert-dismissible" role="alert">
                Your Fees is Pending from <?= $date; ?>. Please Contact to Your Teacher to Pay Fees.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php endif; ?>
                    <!-- alert for pending fees end-->

<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">All Paid Fees List</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="already_paid_fees_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {

        const already_paid_fees_table = $('#already_paid_fees_table').DataTable({
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
                url: "<?= base_url() ?>Fees/paid_fees_list",
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
                    title: 'Fees Paid Date',
                    data: 'paid_date',
                    class: 'compact all',
                }, 
                {
                    title: 'Paid Amount',
                    data: 'fees_amount',
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

    
        /* ------------------------------ Edit Student Form ----------------------------- */
        already_paid_fees_table.on('click', '.pay_pending_fees', function() {
            const row = $(this).closest('tr');
            const showtd = already_paid_fees_table.row(row).data();
           
            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Student</h5>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-6">
                        <label class="form-label" for="student_name">Student Name</label>
                        <input type="text" class="form-control" id="student_name" value="${showtd.student_name}" autofocus readonly>
                    </div>
                      <div class="col-6 mb-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="${showtd.email}" autofocus readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-6">
                        <label class="form-label" for="course_name">Course Name</label>
                        <input type="text" class="form-control" id="course_name" value="${showtd.course_name}" autofocus readonly>
                    </div>
                      <div class="col-6 mb-6">
                        <label class="form-label" for="fees_amount">Fees Amount</label>
                        <input type="number" class="form-control" id="fees_amount" value="${showtd.fees}" autofocus readonly>
                    </div>
                </div>

                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="pay_pending_fees">Proceed to Pay</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            /* ---------------------------- Save Edited Student Data --------------------------- */

            $('#pay_pending_fees').click(function() {
                const params = {
                    valid: true,
                    student_id: showtd.student_id,
                    student_name: $('#student_name').val(),
                    email: $('#email').val(),
                    fees_amount: $('#fees_amount').val(),
                   
                }

                $.ajax({
                    url: '<?= base_url() ?>Fees/pay_pending_fees',
                    method: 'POST',
                    dataType: 'JSON',
                    data: params,
                    success: function(res) {
                        if (res.Resp_code === 'RCS') {
                            toastr.info(res.Resp_desc)
                            $('#back_to_first_screen').click()
                            already_paid_fees_table.ajax.reload()
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