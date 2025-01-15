<?php
$courses = get_courses();
?>

<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Manage Teachers</h5>
        <button type="button" class="btn btn-primary" id="add_teacher">Add Teacher</button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="teacher_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {
        const courses = <?= json_encode($courses) ?>;

        const students_table = $('#teacher_table').DataTable({
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
                url: "<?= base_url() ?>Teachers/get_teacher",
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
                    title: 'Mobile',
                    data: 'mobile',
                    class: 'compact all',
                },
                {
                    title: 'Name of Bank',
                    data: 'bank_name',
                    class: 'compact all',
                }, 
                {
                    title: 'Account Holder Name',
                    data: 'account_holder_name',
                    class: 'compact all',
                }, 
                {
                    title: 'IFSC Code',
                    data: 'ifsc_code',
                    class: 'compact all',
                },
                {
                    title: 'Account Number',
                    data: 'account_number',
                    class: 'compact all',
                },
                {
                    title: 'Salary',
                    data: 'salary',
                    class: 'compact all',
                },
                {
                    title: 'Account Status',
                    data: 'account_status',
                    class: 'compact all ',
                },
                {
                    title: 'Action',
                    data: null,
                    class: 'compact all',
                    render: function(data, type, full, meta) {
                        return `
                            <div class="d-flex">
                                <a class="dropdown-item edit_student" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item delete_student" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
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

        /* ---------------------------- Add Student Form ---------------------------- */

        $('#add_teacher').click(function(e) {

            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add Student</h5>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-6">
                        <label class="form-label" for="student_name">Student Name</label>
                        <input type="text" class="form-control" id="student_name" placeholder="Enter Student Name" autofocus>
                    </div>
                      <div class="col-6 mb-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email Address" autofocus>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-6 mb-6">
                        <label class="form-label" for="mobile">Mobile</label>
                        <input type="number" class="form-control" id="mobile" placeholder="Enter Student Number" autofocus>
                    </div>

                    <div class="col-6 mb-6">
                        <label class="form-label" for="address">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="Enter Your Address" autofocus>
                    </div>
                </div>

                 <div class="row mt-2">
                    <div class="col-6 mb-6">
                        <label class="form-label" for="father_name">Father Name</label>
                        <input type="text" class="form-control" id="father_name" placeholder="Enter Father Name" autofocus>
                    </div>

                    <div class="col-6 mb-6">
                        <label class="form-label" for="mother_name">Mother Name</label>
                        <input type="text" class="form-control" id="mother_name" placeholder="Enter Mother Name" autofocus>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="mb-6">
                        <label class="form-label" for="course_name">Courses</label>
                        <select class="form-control" id="course_name">
                            <option value="">Select Course</option>
                            ${courses.map(courses => `<option value="${courses.id}">${courses.course_name}</option>`).join()}
                        </select>
                    </div>
                </div>
                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="save_student">Save</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            /* ------------------------------ Save Student Data------------------------------ */
            $('#save_student').click(function(e) {
                const params = {
                    valid: true,
                    student_name: $('#student_name').val(),
                    email: $('#email').val(),
                    mobile: $('#mobile').val(),
                    address: $('#address').val(),
                    father_name: $('#father_name').val(),
                    mother_name: $('#mother_name').val(),
                    course_name: $('#course_name').val()

                }

                if (params.student_name === '') {
                    toastr.error('Enter Student Name');
                    params.valid = false;
                    return false;
                }


                if (params.email === '') {
                    toastr.error('Enter Email Address');
                    params.valid = false;
                    return false;
                }

                if (params.mobile === '') {
                    toastr.error('Enter Your Mobile');
                    params.valid = false;
                    return false;
                }

                if (params.address === '') {
                    toastr.error('Enter Your Address');
                    params.valid = false;
                    return false;
                }

                if (params.father_name === '') {
                    toastr.error('Enter Your Father Name');
                    params.valid = false;
                    return false;
                }

                if (params.mother_name === '') {
                    toastr.error('Enter Your Mother Name');
                    params.valid = false;
                    return false;
                }

                if (params.course_name === '') {
                    toastr.error('Select Course');
                    params.valid = false;
                    return false;
                }
                if (params.valid) {
                    $.ajax({
                        url: '<?= base_url() ?>Student/add_student',
                        method: 'POST',
                        dataType: 'JSON',
                        data: params,
                        success: function(res) {
                            if (res.Resp_code === 'RCS') {
                                toastr.info(res.Resp_desc)
                                $('#back_to_first_screen').click()
                                students_table.ajax.reload()
                            } else if (res.Resp_code === 'RLD') {
                                window.location.reload();
                            } else {
                                toastr.error(res.Resp_desc)
                            }
                        }
                    })
                }
            })
        });

        /* ------------------------------ Edit Student Form ----------------------------- */
        students_table.on('click', '.edit_student', function() {
            const row = $(this).closest('tr');
            const showtd = students_table.row(row).data();
           
            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Student</h5>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-6">
                        <label class="form-label" for="student_name">Student Name</label>
                        <input type="text" class="form-control" id="student_name" placeholder="Enter Student Name" value="${showtd.student_name}" autofocus>
                    </div>
                      <div class="col-6 mb-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email Address" value="${showtd.email}" autofocus>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-6 mb-6">
                        <label class="form-label" for="mobile">Mobile</label>
                        <input type="number" class="form-control" id="mobile" placeholder="Enter Student Number" value="${showtd.mobile}" autofocus>
                    </div>

                    <div class="col-6 mb-6">
                        <label class="form-label" for="address">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="Enter Your Address" value="${showtd.address}" autofocus>
                    </div>
                </div>

                 <div class="row mt-2">
                    <div class="col-6 mb-6">
                        <label class="form-label" for="father_name">Father Name</label>
                        <input type="text" class="form-control" id="father_name" placeholder="Enter Father Name" value="${showtd.father_name}" autofocus>
                    </div>

                    <div class="col-6 mb-6">
                        <label class="form-label" for="mother_name">Mother Name</label>
                        <input type="text" class="form-control" id="mother_name" placeholder="Enter Mother Name" value="${showtd.mother_name}" autofocus>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-6 mb-6">
                        <label class="form-label" for="course_name">Courses</label>
                        <select class="form-control" id="course_name">
                            <option value="">Select Course</option>
                            ${courses.map(courses => `<option value="${courses.id}" ${courses.id == showtd.course ? 'selected' : ''}>${courses.course_name}</option>`).join()}
                        </select>
                    </div>
                    <div class="col-6 mb-6">
                        <label class="form-label" for="course_name">Account Status</label>
                        <select class="form-control" id="account_status">
                            <option value="">Select Account Status</option>
                          <option value="ACTIVE" ${showtd.account_status == 'ACTIVE' ? 'selected' : ''} >Active</option>
                             <option value="PENDING" ${showtd.account_status == 'PENDING' ? 'selected' : ''} >Pending</option>
                                <option value="BLOCKED" ${showtd.account_status == 'BLOCKED' ? 'selected' : ''} >Blocked</option>
                                   <option value="INACTIVE" ${showtd.account_status == 'INACTIVE' ? 'selected' : ''} >Inactive</option>
                        </select>
                    </div>
                </div>
                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="edit_student">Save</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            /* ---------------------------- Save Edited Student Data --------------------------- */

            $('#edit_student').click(function() {
                const params = {
                    valid: true,
                    student_id: showtd.student_id,
                    student_name: $('#student_name').val(),
                    email: $('#email').val(),
                    mobile: $('#mobile').val(),
                    address: $('#address').val(),
                    father_name: $('#father_name').val(),
                    mother_name: $('#mother_name').val(),
                    course_id: $('#course_name').val(),
                    account_status : $('#account_status').val() 
                }

                $.ajax({
                    url: '<?= base_url() ?>Student/edit_student',
                    method: 'POST',
                    dataType: 'JSON',
                    data: params,
                    success: function(res) {
                        if (res.Resp_code === 'RCS') {
                            toastr.info(res.Resp_desc)
                            $('#back_to_first_screen').click()
                            students_table.ajax.reload()
                        } else if (res.Resp_code === 'RLD') {
                            window.location.reload();
                        } else {
                            toastr.error(res.Resp_desc)
                        }
                    }
                })
            })




        })

        /* ----------------------------- Delete Student ---------------------------- */
        students_table.on('click', '.delete_student', function() {
            const row = $(this).closest('tr');
            const showtd = students_table.row(row).data();


            $.ajax({
                url: '<?= base_url() ?>Student/delete_student',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    student_id: showtd.student_id
                },
                success: function(res) {
                    if (res.Resp_code === 'RCS') {
                        toastr.info(res.Resp_desc)
                        students_table.ajax.reload()
                    } else if (res.Resp_code === 'RLD') {
                        window.location.reload();
                    } else {
                        toastr.error(res.Resp_desc)
                    }
                }
            })

        })
    })
</script>