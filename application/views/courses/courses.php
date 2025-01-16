<?php
$course_categories = get_course_categories();
?>
<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Manage Courses</h5>
        <button type="button" class="btn btn-primary" id="add_course">Add Course</button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="courses_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {
        const course_categories = <?= json_encode($course_categories) ?>;

        const courses_table = $('#courses_table').DataTable({
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
                url: "<?= base_url() ?>Courses/get_courses",
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
                    title: 'Course Name',
                    data: 'course_name',
                    class: 'compact all',
                },
                {
                    title: 'Course Category',
                    data: 'category_name',
                    class: 'compact all',
                },
                {
                    title: 'Course Duration',
                    data: 'course_duration',
                    class: 'compact all',
                },
                {
                    title: 'Course Fees',
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
                    title: 'Account Statement',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Account Statement',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }

                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Account Statement',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL'

                },

            ]
        })


        /* ------------------------------- Add Course ------------------------------- */
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
                        <label class="form-label" for="course_name">Course Name</label>
                        <input type="text" class="form-control" id="course_name" placeholder="Enter Course Name" autofocus>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="category_name">Course Duration</label>
                        <input type="text" class="form-control" id="course_duration" placeholder="Enter Course Duration" autofocus>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="course_fees">Course Fees</label>
                        <input type="text" class="form-control" id="course_fees" placeholder="Enter Course Fees" autofocus>
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
                    course_duration: $('#course_duration').val(),
                    course_fees: $('#course_fees').val()

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

                if (params.course_fees === '') {
                    toastr.error('Enter Course Fees');
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
                                courses_table.ajax.reload()
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
        courses_table.on('click', '.edit_course', function() {
            const row = $(this).closest('tr');
            const showtd = courses_table.row(row).data();

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
                        <label class="form-label" for="course_name">Course Name</label>
                        <input type="text" class="form-control" id="course_name" placeholder="Enter Course Name" value="${showtd.course_name}" autofocus>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="course_duration">Course Duration</label>
                        <input type="text" class="form-control" id="course_duration" placeholder="Enter Course Duration" value="${showtd.course_duration}" autofocus>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="course_fees">Course Duration</label>
                        <input type="text" class="form-control" id="course_fees" placeholder="Enter Course Duration" value="${showtd.fees}" autofocus>
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
                    course_fees: $('#course_fees').val(),
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
                            courses_table.ajax.reload()
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
        courses_table.on('click', '.delete_course', function() {
            const row = $(this).closest('tr');
            const showtd = courses_table.row(row).data();


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
                        courses_table.ajax.reload()
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