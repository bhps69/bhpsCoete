  <fieldset id="step2">
    <div class="col-md-6 mt-10 form-group">
  		<label>City</label>
                <input type="text" name="mepr_city" placeholder="City" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>State / Province</label>
		<?php $states = $options['custom_fields'][7]->options; ?>
                <select name="mepr_state_province" id="mepr_state_province" class="coete-input mepr-select-field  "  >
                    <option value="">Select</option>
                    <?php foreach ($states as $state) { ?>
                    <option value="<?php echo $state->option_value ?>"><?php echo $state->option_name ?></option>
                    <?php } ?>
                </select>
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>Zip / postal code</label>
                <input type="text" name="mepr_zip_postal_code" class="mepr_zip_postal_code" placeholder="Zip / postal code" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<?php $codes = $options['custom_fields'][11]->options; ?>
  		<label>Country code</label>
  		<select name="mepr_country_code">
                    <option value="">Select</option>
                    <?php foreach ($codes as $code) { ?>
                    <option value="<?php echo $code->option_value; ?>" <?php if ($code->option_value == '1') { ?>selected<?php } ?>>
                        <?php echo $code->option_name; ?>
                    </option>
                    <?php } ?>
                </select>
  	</div>
  	<div class="clearfix"></div>
        <div class="col-md-6 mt-10 form-group">
  		<label>Phone</label>
                <input type="text" name="mepr_phone" class="mepr_phone" placeholder="00000000000" />
		</div>
		<div class="col-md-6 mt-10 form-group">
			<label required>Email </label>
					<input type="email" name="user_email" id="user_email" placeholder="Email" />
		</div>
  	<div class="clearfix"></div>
        
        <div class="col-md-6 mt-10 form-group">
  		<label>Password <span class="text-red">*</span></label>
                <input type="password" name="mepr_user_password" id="mepr_user_password" placeholder="********" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>Confirm Password <span class="text-red">*</span></label>
                <input type="password" name="mepr_user_password_confirm" id="mepr_user_password_confirm" placeholder="" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 text-center">
  		<input type="button" name="previous" class="previous action-button" value="Previous" />
  		<input type="button" name="next" class="next action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
    
  </fieldset>

