<form id="form" action="<?php echo get_site_url(); ?>/wp-admin/admin-post.php" method="post">
  <div class="form-group">
    (<span class="required">*</span> indicates a required field)
  </div>

  <div class="form-group">
    <label for="first"><span class="required">*</span>First Name:</label>
    <input type="text" id="first" name="first" tabindex="2">
  </div>

  <div class="form-group">
    <label for="last"><span class="required">*</span>Last Name:</label>
    <input type="text" id="last" name="last" tabindex="3">
  </div>

  <div class="form-group">
    <label for="rbEmail">
      <span class="required">*</span>Preferred method of contact:
    </label>
    <input type="radio" id="rbEmail" name="preference" value="Email" tabindex="-1">
    <label id="lblEmail" for="rbEmail" class="rb-label" tabindex="4">
      <span class="rb-span"><i></i></span>Email
    </label>
    <input type="radio" id="rbPhone" name="preference" value="Phone" tabindex="-1">
    <label id="lblPhone" for="rbPhone" class="rb-label" tabindex="5">
      <span class="rb-span"><i></i></span>Phone
    </label>
  </div>

  <div class="form-group">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" tabindex="6">
  </div>

  <div class="form-group">
    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" tabindex="7">
  </div>

  <div class="form-group">
    <label for="problems">
      <span class="required">*</span>What problems do you need help solving?
    </label>
    <textarea id="problems" name="problems" tabindex="8"></textarea>
  </div>

  <button class="button" id="btnSubmit" tabindex="9">Submit</button>
  <input type="hidden" name="action" value="add_contact">
</form>