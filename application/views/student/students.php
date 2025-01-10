<?php
// $course_categories = get_course_categories();
?>
<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Manage Student</h5>
        <button type="button" class="btn btn-primary" id="add_course">Add Student</button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="students_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {

        const students_table = $('#students_table').DataTable({
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
                url: "<?= base_url() ?>Student/get_students",
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
                    title: 'Mobile',
                    data: 'mobile',
                    class: 'compact all',
                },
                {
                    title: 'Address',
                    data: 'address',
                    class: 'compact all',
                }, 
                {
                    title: 'Father Name',
                    data: 'father_name',
                    class: 'compact all',
                }, 
                {
                    title: 'Mother Name',
                    data: 'mother_name',
                    class: 'compact all',
                },
                {
                    title: 'Course',
                    data: 'course_name',
                    class: 'compact all',
                },
                {
                    title: 'Fees',
                    data: 'fees',
                    class: 'compact all',
                },
                {
                    title: 'Action',
                    data: null,
                    class: 'compact all',
                    render: function(data, type, full, meta) {
                        return `
                            <div class="d-flex">
                                <a class="dropdown-item edit_course" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item delete_course" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
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



        $('#add_course').click(function(e) {

            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add Course</h5>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label class="form-label" for="category_name">Course Category</label>
                        <select class="form-control" id="course_category">
                            <option value="">Select Course Category</option>
                            ${course_categories.map(cat => `<option value="${cat.category_id}">${cat.category_name}</option>`).join()}
                        </select>
                        </div>
                    <div class="mb-6">
                        <label class="form-label" for="category_name">Course Name</label>
                        <input type="text" class="form-control" id="course_name" placeholder="Enter Course Name" autofocus>
                    </div>
                      <div class="mb-6">
                        <label class="form-label" for="category_name">Course Duration</label>
                        <input type="text" class="form-control" id="course_duration" placeholder="Enter Course Duration" autofocus>
                    </div>
                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="save_course">Save</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            /* ------------------------------ Add Category ------------------------------ */
            $('#save_course').click(function(e) {
                const params = {
                    valid: true,
                    course_category: $('#course_category').val(),
                    course_name: $('#course_name').val(),
                    course_duration: $('#course_duration').val()

                }

                if (params.course_category === '') {
                    toastr.error('Enter Course Category');
                    params.valid = false;
                    return false;
                }


                if (params.course_name === '') {
                    toastr.error('Enter Course Name');
                    params.valid = false;
                    return false;
                }

                if (params.course_duration === '') {
                    toastr.error('Enter Course Duration');
                    params.valid = false;
                    return false;
                }

                if (params.valid) {
                    $.ajax({
                        url: '<?= base_url() ?>Courses/add_course',
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

        /* ------------------------------ Edit Course ----------------------------- */
        students_table.on('click', '.edit_course', function() {
            const row = $(this).closest('tr');
            const showtd = students_table.row(row).data();

            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Course</h5>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label class="form-label" for="category_name">Course Category</label>
                        <select class="form-control" id="course_category">
                            <option value="">Select Course Category</option>
                            ${course_categories.map(cat => `<option value="${cat.category_id}" ${cat.category_id == showtd.course_category ? 'selected' : ''}>${cat.category_name}</option>`).join()}
                        </select>
                        </div>
                    <div class="mb-6">
                        <label class="form-label" for="category_name">Course Name</label>
                        <input type="text" class="form-control" id="course_name" placeholder="Enter Course Name" value="${showtd.course_name}" autofocus>
                    </div>
                      <div class="mb-6">
                        <label class="form-label" for="category_name">Course Duration</label>
                        <input type="text" class="form-control" id="course_duration" placeholder="Enter Course Duration" value="${showtd.course_duration}" autofocus>
                    </div>
                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="edit_course">Save</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            $('#edit_course').click(function() {
                const params = {
                    valid: true,
                    course_category: $('#course_category').val(),
                    course_name: $('#course_name').val(),
                    course_duration: $('#course_duration').val(),
                    course_id: showtd.id
                }

                $.ajax({
                    url: '<?= base_url() ?>Courses/edit_course',
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

        /* ----------------------------- Delete Course ---------------------------- */
        students_table.on('click', '.delete_course', function() {
            const row = $(this).closest('tr');
            const showtd = students_table.row(row).data();


            $.ajax({
                url: '<?= base_url() ?>Courses/delete_course',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    course_id: showtd.id
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