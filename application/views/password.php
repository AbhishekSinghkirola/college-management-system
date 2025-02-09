<div class="row">
    <div class="col-md-12">
      <div class="nav-align-top mb-4">
        <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
          <li class="nav-item">
          <a class="nav-link active" href="<?= base_url(); ?>Dashboard/user_setting"><i class="icon-base bx bx-user icon-sm me-1_5"></i> Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url(); ?>Dashboard/password"><i class="icon-base bx bx-lock-alt icon-sm me-1_5"></i> Security</a>
          </li>
       
        </ul>
      </div>
      <!-- Change Password -->
      <div class="card mb-6">
        <h5 class="card-header">Change Password</h5>
        <div class="card-body pt-1">
          <form id="formAccountSettings" method="GET" onsubmit="return false" class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
            <div class="row">
              <div class="mb-6 col-md-6 form-password-toggle form-control-validation fv-plugins-icon-container">
                <label class="form-label" for="currentPassword">Current Password</label>
                <div class="input-group input-group-merge has-validation">
                  <input class="form-control" type="password" name="currentPassword" id="currentPassword" placeholder="············">
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              </div>
            </div>
            <div class="row">
              <div class="mb-6 col-md-6 form-password-toggle form-control-validation fv-plugins-icon-container">
                <label class="form-label" for="newPassword">New Password</label>
                <div class="input-group input-group-merge has-validation">
                  <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="············">
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              </div>

              <div class="mb-6 col-md-6 form-password-toggle form-control-validation fv-plugins-icon-container">
                <label class="form-label" for="confirmPassword">Confirm New Password</label>
                <div class="input-group input-group-merge has-validation">
                  <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="············">
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              </div>
            </div>
            <h6 class="text-body mt-3 ">Password Requirements:</h6>
            <ul class="ps-4 mb-0">
              <li class="mb-4">Minimum 8 characters long - the more, the better</li>
              <li class="mb-4">At least one lowercase character</li>
              <li>At least one number, symbol, or whitespace character</li>
            </ul>
            <div class="mt-4">
              <button type="submit" class="btn btn-primary me-3" id="save_password">Save changes</button>
              <button id="reset" type="reset" class="btn btn-secondary">Reset</button>
            </div>
          <input type="hidden"></form>
        </div>
      </div>
      <!--/ Change Password -->
  </div>

  <script>
     $('#save_password').click(function(e) {
                const params = {
                    valid: true,
                    current_password: $('#currentPassword').val(),
                    new_password: $('#newPassword').val(),
                    confirm_password: $('#confirmPassword').val(),                   
                }

                if (params.current_password === '') {
                    toastr.error('Enter Current Password');
                    params.valid = false;
                    return false;
                }

                if (params.new_password === '') {
                    toastr.error('Enter New Password');
                    params.valid = false;
                    return false;
                }

                if (params.confirm_password === '') {
                    toastr.error('Enter Confirm Password');
                    params.valid = false;
                    return false;
                }

                if(params.new_password !== params.confirm_password){
                    toastr.error('Confirm Password does not match');
                    params.valid = false;
                    return false;
                }

                if (params.valid) {
                    $.ajax({
                        url: '<?= base_url() ?>Dashboard/change_password',
                        method: 'POST',
                        dataType: 'JSON',
                        data: params,
                        success: function(res) {
                            if (res.Resp_code === 'RCS') {
                                toastr.info(res.Resp_desc)
                                $("#formAccountSettings")[0].reset();

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
            });
 
 
            /* ----------------------------- Reset Password ----------------------------- */

            $('#reset').click(function(e){

                $.ajax({
                        url: '<?= base_url() ?>Dashboard/send_reset_link',
                        method: 'POST',
                        dataType: 'JSON',
                       
                        success: function(res) {
                            if (res.Resp_code === 'RCS') {
                                toastr.info(res.Resp_desc)
                                $("#formAccountSettings")[0].reset();

                                $('#back_to_first_screen').click()
                                students_table.ajax.reload()
                            } else if (res.Resp_code === 'RLD') {
                                window.location.reload();
                            } else {
                                toastr.error(res.Resp_desc)
                            }
                        }
                    });

            });
 
 </script>