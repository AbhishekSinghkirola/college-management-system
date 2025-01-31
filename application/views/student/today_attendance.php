<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Today Attendance</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="attendance_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {
        const attendance_table = $('#attendance_table').DataTable({
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
                url: "<?= base_url() ?>Student/student_today_attendance",
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
                    title: 'Attendance Status',
                    data: null,
                    class: 'compact all',
                    render: function(data, type, full, meta) {
                        if (full.status === 'PENDING') {
                            return `
                            <div class="d-flex gap-4">
                                <a class="btn btn-primary present_student" style="width:max-content;" href="javascript:void(0);"> Mark Present</a>
                                <a class="btn btn-danger absent_student" style="width:max-content;" href="javascript:void(0);"> Mark Absent</a>
                            </div>
                        `;
                        } else {
                            return full.status == 1 ? 'PRESENT' : 'ABSENT';
                        }

                    }
                }
            ],
            buttons: [{
                    extend: 'csv',
                    className: 'btn btn-info ml-2',
                    title: 'Student Today Attendance',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Student Today Attendance',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }

                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Student Today Attendance',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL'

                },

            ]
        });

        /* ----------------------------- Present Student ---------------------------- */
        attendance_table.on('click', '.present_student', function() {
            const row = $(this).closest('tr');
            const showtd = attendance_table.row(row).data();

            const params = {
                valid: true,
                student_id: showtd.student_id,
                status: 'PRESENT',
            }

            if (params.valid) {
                $.ajax({
                    url: '<?= base_url() ?>Student/mark_attendance',
                    method: 'POST',
                    dataType: 'JSON',
                    data: params,
                    success: function(res) {
                        if (res.Resp_code === 'RCS') {
                            toastr.info(res.Resp_desc)
                            $('#back_to_first_screen').click()
                            attendance_table.ajax.reload()
                        } else if (res.Resp_code === 'RLD') {
                            window.location.reload();
                        } else {
                            toastr.error(res.Resp_desc)
                        }
                    }
                })
            }
        })

        /* ----------------------------- Absent Student ----------------------------- */
        attendance_table.on('click', '.absent_student', function() {
            const row = $(this).closest('tr');
            const showtd = attendance_table.row(row).data();

            const params = {
                valid: true,
                student_id: showtd.student_id,
                status: 'ABSENT',
            }

            if (params.valid) {
                $.ajax({
                    url: '<?= base_url() ?>Student/mark_attendance',
                    method: 'POST',
                    dataType: 'JSON',
                    data: params,
                    success: function(res) {
                        if (res.Resp_code === 'RCS') {
                            toastr.info(res.Resp_desc)
                            $('#back_to_first_screen').click()
                            attendance_table.ajax.reload()
                        } else if (res.Resp_code === 'RLD') {
                            window.location.reload();
                        } else {
                            toastr.error(res.Resp_desc)
                        }
                    }
                })
            }
        })
    })
</script>