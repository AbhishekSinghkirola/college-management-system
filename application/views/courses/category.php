<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Manage Course Category</h5>
        <button type="button" class="btn btn-primary" id="add_category">Add Category</button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="category_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {

        const category_table = $('#category_table').DataTable({
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
                url: "<?= base_url() ?>Courses/get_categories",
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
                    title: 'Category Name',
                    data: 'category_name',
                    class: 'compact all',
                },
                {
                    title: 'Action',
                    data: null,
                    class: 'compact all',
                    render: function(data, type, full, meta) {
                        return `
                            <div class="d-flex">
                                <a class="dropdown-item edit_category" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item delete_category" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
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



        $('#add_category').click(function(e) {

            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add Category</h5>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label class="form-label" for="category_name">Category Name</label>
                        <input type="text" class="form-control" id="category_name" placeholder="Enter Category Name" autofocus>
                    </div>
                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="save_category">Send</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            /* ------------------------------ Add Category ------------------------------ */
            $('#save_category').click(function(e) {
                const params = {
                    valid: true,
                    cat_name: $('#category_name').val(),

                }
                if (params.cat_name === '') {
                    toastr.error('Enter category Name');
                    params.valid = false;
                    return false;
                }

                if (params.valid) {
                    $.ajax({
                        url: '<?= base_url() ?>Courses/add_category',
                        method: 'POST',
                        dataType: 'JSON',
                        data: params,
                        success: function(res) {
                            if (res.Resp_code === 'RCS') {
                                toastr.info(res.Resp_desc)
                                $('#back_to_first_screen').click()
                                category_table.ajax.reload()
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

        /* ------------------------------ Edit Category ----------------------------- */
        category_table.on('click', '.edit_category', function() {
            const row = $(this).closest('tr');
            const showtd = category_table.row(row).data();

            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Category</h5>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label class="form-label" for="category_name">Category Name</label>
                        <input type="text" class="form-control" id="category_name" placeholder="Enter Category Name" value="${showtd.category_name}" autofocus>
                    </div>
                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="update_category">Update</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            $('#update_category').click(function() {
                const category_name = $('#category_name').val();
                console.log(category_name);

                $.ajax({
                    url: '<?= base_url() ?>Courses/edit_category',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        cat_id: showtd.category_id,
                        category_name,
                    },
                    success: function(res) {
                        if (res.Resp_code === 'RCS') {
                            toastr.info(res.Resp_desc)
                            $('#back_to_first_screen').click()
                            category_table.ajax.reload()
                        } else if (res.Resp_code === 'RLD') {
                            window.location.reload();
                        } else {
                            toastr.error(res.Resp_desc)
                        }
                    }
                })
            })




        })

        /* ----------------------------- Delete Category ---------------------------- */
        category_table.on('click', '.delete_category', function() {
            const row = $(this).closest('tr');
            const showtd = category_table.row(row).data();


            $.ajax({
                url: '<?= base_url() ?>Courses/delete_category',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    cat_id: showtd.category_id
                },
                success: function(res) {
                    if (res.Resp_code === 'RCS') {
                        toastr.info(res.Resp_desc)
                        category_table.ajax.reload()
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