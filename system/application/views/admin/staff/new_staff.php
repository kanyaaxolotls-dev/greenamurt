<div class="col">
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">Create New Staff</h3>
                </div>
                <div class="col-4 text-right">
                    <a href="<?php echo site_url('admin') ?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <?php echo form_open('staff/new_staff', array("class" => "form-group")); ?>

            <div class="row">
                <!-- Designation Dropdown -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="designation" class="control-label">Designation</label>
                        <select name="designation" class="form-control form-control-alternative" required >
                            <option value="" disabled selected>Select Designation</option>
                            <?php foreach ($data as $e) { ?>
                                <option value="<?php echo $e->id ?>"><?php echo $e->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- Name Input -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name" class="control-label"><span class="text-danger">*</span> Name</label>
                        <input type="text" 
                               name="name" 
                               value="<?php echo set_value('name'); ?>" 
                               class="form-control form-control-alternative" 
                               id="name"
                               placeholder="Enter full name"  required />
                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                    </div>
                </div>

                <!-- Email Input -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" 
                               name="email" 
                               value="<?php echo set_value('email'); ?>" 
                               class="form-control form-control-alternative" 
                               id="email"
                               placeholder="Enter email address"  required />
                        <span class="text-danger"><?php echo form_error('email'); ?></span>
                    </div>
                </div>

                <!-- Username Input -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username" class="control-label"><span class="text-danger">*</span> Username</label>
                        <input type="text" 
                               name="username" 
                               value="<?php echo set_value('username'); ?>" 
                               class="form-control form-control-alternative" 
                               id="username"
                               placeholder="Enter username"  required />
                        <span class="text-danger"><?php echo form_error('username'); ?></span>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <input type="password" 
                               name="password" 
                               value="<?php echo set_value('password'); ?>" 
                               class="form-control form-control-alternative" 
                               id="password"
                               placeholder="Enter password" required />
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-sm-12">
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
