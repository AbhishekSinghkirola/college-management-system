<?php
$courses = get_courses() ?? [];
$content = get_content() ?? [];
?>
<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Assign Content To Courses</h5>
        <button type="button" class="btn btn-primary" id="assign_content">Assign Content</button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="data_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {
        const courses = <?= json_encode($courses) ?>;
        const content = <?= json_encode($content) ?>;
        console.log(courses, content);

        const datatable = $('#data_table').DataTable({
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
                url: "<?= base_url() ?>Courses/get_assigned_content",
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
                    title: 'Content Name',
                    data: 'content_name',
                    class: 'compact all',
                },
                {
                    title: 'Action',
                    data: null,
                    class: 'compact all',
                    render: function(data, type, full, meta) {
                        return `
                            <div class="d-flex">
                                <a class="dropdown-item edit_relation" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item delete_relation" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                            </div>
                        `;
                    }
                }
            ],
            buttons: [{
                    extend: 'csv',
                    className: 'btn btn-info ml-2',
                    title: 'Course Content',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Course Content',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }

                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Course Content',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL'

                },

            ]
        });

        /* ----------------------------- Assign Content ----------------------------- */
        $('#assign_content').click(function(e) {

            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assign Content</h5>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label class="form-label" for="course_name">Course</label>
                        <select class="form-control" id="course_name">
                            <option value="">Select Course</option>
                            ${courses.map(course => `<option value="${course.id}">${course.course_name}</option>`).join()}
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="content_name">Content</label>
                        <select class="form-control" id="content_name">
                            <option value="">Select Content</option>
                            ${content.map(con => `<option value="${con.id}">${con.name}</option>`).join()}
                        </select>
                    </div>
                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="assign">Save</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            /* ------------------------------ Assign Content ------------------------------ */
            $('#assign').click(function(e) {
                const params = {
                    valid: true,
                    course_name: $('#course_name').val(),
                    content_name: $('#content_name').val()

                }

                if (params.course_name === '') {
                    toastr.error('Enter Course Name');
                    params.valid = false;
                    return false;
                }

                if (params.content_name === '') {
                    toastr.error('Enter Content Name');
                    params.valid = false;
                    return false;
                }

                if (params.valid) {
                    $.ajax({
                        url: '<?= base_url() ?>Courses/save_assigned_content',
                        method: 'POST',
                        dataType: 'JSON',
                        data: params,
                        success: function(res) {
                            if (res.Resp_code === 'RCS') {
                                toastr.info(res.Resp_desc)
                                $('#back_to_first_screen').click()
                                datatable.ajax.reload()
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

        /* -------------------------- Edit Assigned Content ------------------------- */
        datatable.on('click', '.edit_relation', function() {
            const row = $(this).closest('tr');
            const showtd = datatable.row(row).data();

            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Assigned Content</h5>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label class="form-label" for="edit_course_name">Course</label>
                        <select class="form-control" id="edit_course_name">
                            <option value="">Select Course</option>
                            ${courses.map(course => `<option value="${course.id}" ${course.id == showtd.course_id ? 'selected' : ''}>${course.course_name}</option>`).join()}
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="form-label" for="edit_content_name">Content</label>
                        <select class="form-control" id="edit_content_name">
                            <option value="">Select Content</option>
                            ${content.map(con => `<option value="${con.id}" ${con.id == showtd.content_id ? 'selected' : ''}>${con.name}</option>`).join()}
                        </select>
                    </div>
                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="edit_assigned_content">Save</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            $('#edit_assigned_content').click(function() {
                const params = {
                    valid: true,
                    course_id: $('#edit_course_name').val(),
                    content_id: $('#edit_content_name').val(),
                    assigned_id: showtd.assigned_id
                }

                $.ajax({
                    url: '<?= base_url() ?>Courses/edit_assigned_content',
                    method: 'POST',
                    dataType: 'JSON',
                    data: params,
                    success: function(res) {
                        if (res.Resp_code === 'RCS') {
                            toastr.info(res.Resp_desc)
                            $('#back_to_first_screen').click()
                            datatable.ajax.reload()
                        } else if (res.Resp_code === 'RLD') {
                            window.location.reload();
                        } else {
                            toastr.error(res.Resp_desc)
                        }
                    }
                })
            })

        });

        /* ------------------------- Delte Assigned Content ------------------------- */
        datatable.on('click', '.delete_relation', function() {
            const row = $(this).closest('tr');
            const showtd = datatable.row(row).data();


            $.ajax({
                url: '<?= base_url() ?>Courses/delete_assigned_content',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    assigned_id: showtd.assigned_id
                },
                success: function(res) {
                    if (res.Resp_code === 'RCS') {
                        toastr.info(res.Resp_desc)
                        datatable.ajax.reload()
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