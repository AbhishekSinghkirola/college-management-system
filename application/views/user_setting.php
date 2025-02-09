<?php
$user = get_logged_in_user();

?>

<div class="row fv-plugins-icon-container">
    <div class="col-md-12">
      <div class="nav-align-top mb-3">
        <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
          <li class="nav-item">
            <a class="nav-link active" href="<?= base_url(); ?>Dashboard/user_setting"><i class="icon-base bx bx-user icon-sm me-1_5"></i> Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url(); ?>Dashboard/password"><i class="icon-base bx bx-lock-alt icon-sm me-1_5"></i> Security</a>
          </li>
         
        </ul>
      </div>
      <div class="card mb-4">
        <!-- Account -->
       
        <div class="card-body pt-4">
          <form id="formAccountSettings" method="GET" onsubmit="return false" class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
            <div class="row g-6">
              <div class="col-md-6 form-control-validation fv-plugins-icon-container">
                <label for="firstName" class="form-label">First Name</label>
                <input class="form-control" type="text" id="firstName" name="firstName"  readonly value="<?= $user['first_name']; ?>" autofocus="">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-md-6 form-control-validation fv-plugins-icon-container">
                <label for="lastName" class="form-label">Last Name</label>
                <input class="form-control" type="text" name="lastName" id="lastName"  readonly value="<?= $user['last_name']; ?>">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-md-6">
                <label for="email" class="form-label">E-mail</label>
                <input class="form-control" type="text" id="email" name="email" readonly  value="<?= $user['email']; ?>" placeholder="john.doe@example.com">
              </div>
              
              <div class="col-md-6">
                <label class="form-label" for="phoneNumber">Phone Number</label>
                <div class="input-group input-group-merge">
                 
                  <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="" readonly  value="<?= $user['mobile'] ?>">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="" readonly  value="<?= $user['address'] ?>">
              </div>
            
              
            </div>
            
          <input type="hidden"></form>
        </div>
        <!-- /Account -->
      </div>
     
    </div>
  </div>