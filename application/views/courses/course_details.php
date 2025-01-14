<div class="col-md-8">
    <div class="card bg-primary text-white mb-3">
        <div class="card-header text-uppercase d-flex justify-content-between">
            <h5 class="card-title text-white text-uppercase">course category - <?= $course_details['category_name'] ?></h5>
            <p>course duration - <?= $course_details['course_duration'] ?></p>
        </div>
        <div class="card-body d-flex justify-content-between">
            <p class="card-text text-uppercase">course name - <?= $course_details['course_name'] ?></p>
            <p class="card-text text-uppercase">course fees - <?= $course_details['fees'] ?></p>

        </div>
    </div>
</div>


<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Course Content</h5>
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
        const courseDetails = <?= json_encode($course_details) ?>;

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
                url: "<?= base_url() ?>Courses/get_student_courses_content",
                type: "POST",
                data: function(d) {
                    d.course_id = courseDetails.id;
                },
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
                title: 'Content Name',
                data: 'name',
                class: 'compact all',
            }],
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
        })
    })
</script>