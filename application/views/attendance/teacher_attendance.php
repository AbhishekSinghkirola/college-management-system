
<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Attendance Details</h5>
        <?php if (empty($attendance)) : ?>
            <button type="button" class="btn btn-primary" id="mark_attendance">Mark Your Attendance</button>
        <?php endif; ?>
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
                url: "<?= base_url() ?>Attendance/get_attendance",
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
                    title: 'Date',
                    data: 'date',
                    class: 'compact all',
                },
                {
                    title: 'Status',
                    data: 'status',
                    class: 'compact all',
                    render: function(data, type, full, meta) {
                        if (full.status == 1) {
                            return 'Present';
                        } else {
                            return 'Absent';
                        }
                    }
                },
            ],
            buttons: [{
                    extend: 'csv',
                    className: 'btn btn-info ml-2',
                    title: 'Student attendance',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Student attendance',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }

                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Student attendance',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL'

                },

            ]
        });

        /* ----------------------------- Mark attendance ---------------------------- */
        $('#mark_attendance').click(function() {
            $.ajax({
                url: '<?= base_url() ?>Attendance/mark_attendance',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.Resp_code == 'RCS') {
                        toastr.success(response.Resp_desc)
                        $('#mark_attendance').hide();
                        attendance_table.ajax.reload();
                    } else if (response.Resp_code === 'RLD') {
                        window.location.reload(true);
                    } else {
                        toastr.error(response.Resp_desc)
                    }
                }
            })
        })
    })
</script>