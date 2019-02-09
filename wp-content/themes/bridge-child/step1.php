  <fieldset id="step1">
  	<div class="col-md-6 mt-10 form-group">
  		<label>Company Name <span class="text-red">*</span></label>
                <input type="text" name="mepr_company_name" id="mepr_company_name" placeholder="Company Name" />
  	</div>
  	<div class="col-md-6 mt-10 form-group">
  		<label>Country</label>
  		<?php
                    $options = get_option(' mepr_options ');
                    $countries = $options['custom_fields'][3]->options;
                ?>
                <select name="mepr_country" id="mepr_country" class="coete-input mepr-select-field">
                    <option value="" >Select</option>
                    <?php foreach ($countries as $contry) { ?>
                    <option value="<?php echo $contry->option_value ?>" <?php if ($contry->option_value == 'usa') ?>selected>
                    <?php echo $contry->option_name ?>
                    </option>
                    <?php } ?>        
                </select>
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 mt-10 form-group">
  		<label>Address 1</label>
                <input type="text" name="mepr_address_1" placeholder="Address" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 mt-10 form-group">
  		<label>Address 2</label>
                <input type="text" name="mepr_address_2" placeholder="Address" />
  	</div>
  	<div class="clearfix"></div>
  	<div class="col-md-12 text-center mt-10">
  		<input type="button" name="next" class="next action-button" value="Next" />
  	</div>
  	<div class="clearfix"></div>
  </fieldset>

