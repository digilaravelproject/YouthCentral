<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Add New Business Listing</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('vendor.businesses.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <h6 class="text-uppercase text-sm">Business Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_name" class="form-control-label">Business Name</label>
                                    <input class="form-control <?php $__errorArgs = ['business_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" id="business_name" name="business_name" value="<?php echo e(old('business_name')); ?>" required>
                                    <?php $__errorArgs = ['business_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone" class="form-control-label">Phone Number</label>
                                    <input class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" id="phone" name="phone" value="<?php echo e(old('phone')); ?>" required>
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="whatsapp_number" class="form-control-label">WhatsApp Number</label>
                                    <input class="form-control <?php $__errorArgs = ['whatsapp_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" id="whatsapp_number" name="whatsapp_number" value="<?php echo e(old('whatsapp_number')); ?>">
                                    <?php $__errorArgs = ['whatsapp_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email Address</label>
                                    <input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="email" id="email" name="email" value="<?php echo e(old('email')); ?>">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website" class="form-control-label">Website</label>
                                    <input class="form-control <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="url" id="website" name="website" value="<?php echo e(old('website')); ?>">
                                    <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="text-uppercase text-sm mt-3">Social Media Links</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook_link" class="form-control-label">
                                        <i class="fab fa-facebook text-primary me-1"></i> Facebook Link
                                    </label>
                                    <input class="form-control <?php $__errorArgs = ['facebook_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="url" id="facebook_link" name="facebook_link" value="<?php echo e(old('facebook_link')); ?>">
                                    <?php $__errorArgs = ['facebook_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="instagram_link" class="form-control-label">
                                        <i class="fab fa-instagram text-danger me-1"></i> Instagram Link
                                    </label>
                                    <input class="form-control <?php $__errorArgs = ['instagram_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="url" id="instagram_link" name="instagram_link" value="<?php echo e(old('instagram_link')); ?>">
                                    <?php $__errorArgs = ['instagram_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter_link" class="form-control-label">
                                        <i class="fab fa-twitter text-info me-1"></i> Twitter Link
                                    </label>
                                    <input class="form-control <?php $__errorArgs = ['twitter_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="url" id="twitter_link" name="twitter_link" value="<?php echo e(old('twitter_link')); ?>">
                                    <?php $__errorArgs = ['twitter_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pinterest_link" class="form-control-label">
                                        <i class="fab fa-pinterest text-danger me-1"></i> Pinterest Link
                                    </label>
                                    <input class="form-control <?php $__errorArgs = ['pinterest_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="url" id="pinterest_link" name="pinterest_link" value="<?php echo e(old('pinterest_link')); ?>">
                                    <?php $__errorArgs = ['pinterest_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="horizontal dark mt-4">
                        
                        <h6 class="text-uppercase text-sm">Category & Subcategory</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id" class="form-control-label">Category</label>
                                    <select class="form-control <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="category_id" name="category_id">
                                        <option value="">Select a category</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subcategory_id" class="form-control-label">Subcategory</label>
                                    <select class="form-control <?php $__errorArgs = ['subcategory_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="subcategory_id" name="subcategory_id" required>
                                        <option value="">Select Category First</option>
                                    </select>
                                    <?php $__errorArgs = ['subcategory_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="text-uppercase text-sm mt-3">Location Information</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="street_address" class="form-control-label">Street Address</label>
                                    <div class="input-group position-relative">
                                        <input class="form-control <?php $__errorArgs = ['street_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" id="street_address" name="street_address" value="<?php echo e(old('street_address')); ?>" required autocomplete="off">
                                        <div id="address-suggestions" class="list-group position-absolute" style="z-index:1050; width:100%; display:none;"></div>
                                    </div>
                                    <?php $__errorArgs = ['street_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="state_id" class="form-control-label">State</label>
                                    <select class="form-control <?php $__errorArgs = ['state_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="state_id" name="state_id">
                                        <option value="">Select a state</option>
                                        <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($state->id); ?>" <?php echo e(old('state_id') == $state->id ? 'selected' : ''); ?>><?php echo e($state->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['state_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_id" class="form-control-label">City</label>
                                    <select class="form-control <?php $__errorArgs = ['city_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="city_id" name="city_id" required>
                                        <option value="">Select State First</option>
                                    </select>
                                    <?php $__errorArgs = ['city_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="area_id" class="form-control-label">Area</label>
                                    <select class="form-control <?php $__errorArgs = ['area_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="area_id" name="area_id" required>
                                        <option value="">Select City First</option>
                                    </select>
                                    <?php $__errorArgs = ['area_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude" class="form-control-label">Latitude</label>
                                    <input class="form-control <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" id="latitude" name="latitude" value="<?php echo e(old('latitude')); ?>" required readonly>
                                    <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude" class="form-control-label">Longitude</label>
                                    <input class="form-control <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" id="longitude" name="longitude" value="<?php echo e(old('longitude')); ?>" required readonly>
                                    <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="horizontal dark mt-4">
                        
                        <h6 class="text-uppercase text-sm">Business Description</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description</label>
                                    <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" rows="5"><?php echo e(old('description')); ?></textarea>
                                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="horizontal dark mt-4">
                        
                        <h6 class="text-uppercase text-sm">Business Hours</h6>
                        <div class="row">
                            <?php $__currentLoopData = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label text-capitalize"><?php echo e($day); ?></label>
                                        <div class="d-flex align-items-center">
                                            <input type="time" class="form-control me-2 <?php $__errorArgs = ['business_hours.'.$day.'.opening_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="business_hours[<?php echo e($day); ?>][opening_time]" value="<?php echo e(old('business_hours.'.$day.'.opening_time', '09:30')); ?>">
                                            <span class="mx-2">to</span>
                                            <input type="time" class="form-control me-2 <?php $__errorArgs = ['business_hours.'.$day.'.closing_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="business_hours[<?php echo e($day); ?>][closing_time]" value="<?php echo e(old('business_hours.'.$day.'.closing_time', '18:30')); ?>">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="business_hours[<?php echo e($day); ?>][is_closed]" value="1" id="<?php echo e($day); ?>_closed" <?php echo e(old('business_hours.'.$day.'.is_closed') ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="<?php echo e($day); ?>_closed">Closed</label>
                                            </div>
                                        </div>
                                        <?php $__errorArgs = ['business_hours.'.$day.'.opening_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <?php $__errorArgs = ['business_hours.'.$day.'.closing_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                        <hr class="horizontal dark mt-4">
                        
                        <h6 class="text-uppercase text-sm">Logo & Gallery</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo" class="form-control-label">Business Logo</label>
                                    <input type="file" class="form-control <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="logo" name="logo" accept="image/*">
                                    <small class="text-muted">Recommended size: 200x200px. Max 1MB.</small>
                                    <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gallery_images" class="form-control-label">Gallery Images</label>
                                    <input type="file" class="form-control <?php $__errorArgs = ['gallery_images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="gallery_images" name="gallery_images[]" multiple accept="image/*">
                                    <small class="text-muted">You can select multiple images. Max 2MB per image. (Number allowed depends on your plan)</small>
                                    <?php $__errorArgs = ['gallery_images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4" role="alert">
                            <h4 class="alert-heading">Important Note!</h4>
                            <p>Your business listing will be reviewed by our admin team before it appears in the public directory.</p>
                            <hr>
                            <p class="mb-0">Approval typically takes 1-2 business days. You will be notified once your listing is approved.</p>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?php echo e(route('vendor.businesses.index')); ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Listing</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Function to toggle business hours time inputs based on closed checkbox
    function toggleTimeInputs(checkbox, day) {
        const openingTimeInput = document.querySelector(`input[name="business_hours[${day}][opening_time]"]`);
        const closingTimeInput = document.querySelector(`input[name="business_hours[${day}][closing_time]"]`);
        
        if (!openingTimeInput || !closingTimeInput) {
            console.error(`Could not find time inputs for day: ${day}`);
            return;
        }

        if (checkbox.checked) {
            // If closed, disable time inputs and clear values
            openingTimeInput.disabled = true;
            closingTimeInput.disabled = true;
            openingTimeInput.value = ''; 
            closingTimeInput.value = ''; 
        } else {
            // If open, enable time inputs
            openingTimeInput.disabled = false;
            closingTimeInput.disabled = false;
            // Optionally set default times if they are empty
            if (!openingTimeInput.value) openingTimeInput.value = '09:30';
            if (!closingTimeInput.value) closingTimeInput.value = '18:30';
        }
    }

    $(document).ready(function() {
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // --- Cascading Dropdown Logic --- 

        // Category -> Subcategory dependency
        $('#category_id').change(function() {
            var categoryId = $(this).val();
            var subcategoryDropdown = $('#subcategory_id');
            
            // Remove any existing loader
            $('.subcategory-loader').remove();
            
            subcategoryDropdown.empty().append('<option value="">Loading...</option>'); // Reset with loading

            if(categoryId) {
                // Add visual loader next to Subcategory label
                $('label[for="subcategory_id"]').append('<span class="subcategory-loader ms-2 text-primary" style="font-size: 0.8rem;"><i class="fas fa-spinner fa-spin"></i> Loading...</span>');
                subcategoryDropdown.prop('disabled', true); // Disable while loading
                
                $.ajax({
                    url: '/vendor/businesses/subcategories', // Use Vendor route
                    type: 'GET',
                    data: { category_id: categoryId },
                    dataType: 'json',
                    success: function(data) {
                        subcategoryDropdown.empty(); // Clear loading/previous options
                        if(data.length > 0) {
                             subcategoryDropdown.append('<option value="">Select Subcategory</option>');
                            $.each(data, function(key, value) {
                                subcategoryDropdown.append($('<option>', { 
                                    value: value.id,
                                    text : value.name 
                                }));
                            });
                        } else {
                            subcategoryDropdown.append('<option value="">No subcategories found</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading subcategories:", status, error, xhr.responseText);
                        subcategoryDropdown.empty().append('<option value="">Error loading</option>');
                    },
                    complete: function() {
                        $('.subcategory-loader').remove(); // Remove loader
                        subcategoryDropdown.prop('disabled', false); // Re-enable
                    }
                });
            } else {
                 subcategoryDropdown.empty().append('<option value="">Select Category First</option>');
            }
        });
        
        // State -> City dependency
        $('#state_id').change(function() {
            var stateId = $(this).val();
            var cityDropdown = $('#city_id');
            var areaDropdown = $('#area_id');
            
            // Remove any existing loader
            $('.city-loader').remove();
            
            cityDropdown.empty().append('<option value="">Loading...</option>'); // Reset city with loading
            areaDropdown.empty().append('<option value="">Select City First</option>'); // Reset area

            if(stateId) {
                // Add visual loader next to City label
                $('label[for="city_id"]').append('<span class="city-loader ms-2 text-primary" style="font-size: 0.8rem;"><i class="fas fa-spinner fa-spin"></i> Loading...</span>');
                cityDropdown.prop('disabled', true); // Disable while loading
                
                $.ajax({
                    url: '/vendor/businesses/cities', // Use Vendor route
                    type: 'GET',
                    data: { state_id: stateId },
                    dataType: 'json',
                    success: function(data) {
                        cityDropdown.empty(); // Clear loading/previous options
                        if(data.length > 0) {
                            cityDropdown.append('<option value="">Select a city</option>');
                            $.each(data, function(key, value) {
                                cityDropdown.append($('<option>', { 
                                    value: value.id,
                                    text : value.name 
                                }));
                            });
                        } else {
                             cityDropdown.append('<option value="">No cities found</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading cities:", status, error, xhr.responseText);
                         cityDropdown.empty().append('<option value="">Error loading</option>');
                    },
                    complete: function() {
                        $('.city-loader').remove(); // Remove loader
                        cityDropdown.prop('disabled', false); // Re-enable
                    }
                });
            } else {
                 cityDropdown.empty().append('<option value="">Select State First</option>');
                 areaDropdown.empty().append('<option value="">Select City First</option>');
            }
        });

        // City -> Area dependency
        $('#city_id').change(function() {
            var cityId = $(this).val();
            var areaDropdown = $('#area_id');
            
            // Remove any existing loader
            $('.area-loader').remove();
            
            areaDropdown.empty().append('<option value="">Loading...</option>'); // Reset area with loading

            if(cityId) {
                // Add visual loader next to Area label
                $('label[for="area_id"]').append('<span class="area-loader ms-2 text-primary" style="font-size: 0.8rem;"><i class="fas fa-spinner fa-spin"></i> Loading...</span>');
                areaDropdown.prop('disabled', true); // Disable while loading
                
                $.ajax({
                    url: '/vendor/businesses/areas', // Use Vendor route
                    type: 'GET',
                    data: { city_id: cityId },
                    dataType: 'json',
                    success: function(data) {
                        areaDropdown.empty(); // Clear loading/previous options
                         if(data.length > 0) {
                             areaDropdown.append('<option value="">Select an area</option>');
                            $.each(data, function(key, value) {
                                areaDropdown.append($('<option>', { 
                                    value: value.id,
                                    text : value.name 
                                }));
                            });
                        } else {
                            areaDropdown.append('<option value="">No areas found</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading areas:", status, error, xhr.responseText);
                         areaDropdown.empty().append('<option value="">Error loading</option>');
                    },
                    complete: function() {
                        $('.area-loader').remove(); // Remove loader
                        areaDropdown.prop('disabled', false); // Re-enable
                    }
                });
            } else {
                 areaDropdown.empty().append('<option value="">Select City First</option>');
            }
        });

        // --- Geocoding & Coordinate Toggling Logic ---
        function checkCoordinates() {
            let lat = $('#latitude').val();
            let lng = $('#longitude').val();
            if (lat && lng && lat.trim() !== '' && lng.trim() !== '') {
                $('#latitude').prop('readonly', false).css('background-color', '').css('cursor', '');
                $('#longitude').prop('readonly', false).css('background-color', '').css('cursor', '');
            } else {
                $('#latitude').prop('readonly', true).css('background-color', '#e9ecef').css('cursor', 'not-allowed');
                $('#longitude').prop('readonly', true).css('background-color', '#e9ecef').css('cursor', 'not-allowed');
            }
        }

        // Initialize coordinates state on load
        checkCoordinates();
        
        // Also check whenever inputs are changed manually
        $('#latitude, #longitude').on('input', function() {
            checkCoordinates();
        });

        // --- Address Autocomplete (Nominatim) ---
        (function() {
            let debounceTimer = null;
            const $input = $('#street_address');
            const $suggestions = $('#address-suggestions');

            function closeSuggestions() {
                $suggestions.hide().empty();
            }

            function renderSuggestions(items) {
                $suggestions.empty();
                if (!items || items.length === 0) {
                    closeSuggestions();
                    return;
                }
                items.forEach(function(item) {
                    const $a = $('<a href="#" class="list-group-item list-group-item-action"></a>');
                    $a.text(item.display_name);
                    $a.data('lat', item.lat);
                    $a.data('lon', item.lon);
                    $a.data('details', item);
                    $a.on('click', function(e) {
                        e.preventDefault();
                        const d = $(this).data('details');
                        $input.val(d.display_name);
                        $('#latitude').val(d.lat);
                        $('#longitude').val(d.lon);
                        checkCoordinates();
                        // Try to set state/city/area selects by matching option text
                        tryPopulateLocationFromNominatim(d.address || {});
                        closeSuggestions();
                    });
                    $suggestions.append($a);
                });
                $suggestions.show();
            }

            function tryPopulateLocationFromNominatim(address) {
                // Best-effort: match by option text (case-insensitive)
                function matchAndSelect(selectId, candidates) {
                    if (!candidates || candidates.length === 0) return;
                    const $sel = $(selectId);
                    if ($sel.length === 0) return;
                    const opts = $sel.find('option');
                    const lowerCandidates = candidates.map(c=> (c||'').toLowerCase());
                    let matched = null;
                    opts.each(function() {
                        const txt = ($(this).text()||'').toLowerCase();
                        if (lowerCandidates.indexOf(txt) !== -1) {
                            matched = $(this).val();
                            return false;
                        }
                    });
                    if (matched) {
                        $sel.val(matched).trigger('change');
                    }
                }

                // state
                matchAndSelect('#state_id', [address.state, address.state_district, address.region]);
                // city/town/village
                matchAndSelect('#city_id', [address.city, address.town, address.village, address.county]);
                // area / suburb / neighbourhood
                matchAndSelect('#area_id', [address.suburb, address.neighbourhood, address.city_district]);
            }

            $input.on('input paste', function(e) {
                // If pasted value is a Google Maps URL, try to extract coordinates immediately
                const val = $(this).val().trim();
                if (!val) return;
                const gm = detectGoogleMapsCoordinates(val);
                if (gm) {
                    $('#latitude').val(gm.lat);
                    $('#longitude').val(gm.lon);
                    if (gm.clean) $input.val(gm.clean);
                    checkCoordinates();
                    return; // skip autocomplete lookup when URL contains coords
                }

                // otherwise continue with normal autocomplete
                const q = $(this).val().trim();
                clearTimeout(debounceTimer);
                if (!q) {
                    closeSuggestions();
                    return;
                }
                debounceTimer = setTimeout(function() {
                    $.ajax({
                        url: 'https://nominatim.openstreetmap.org/search',
                        data: { q: q, format: 'json', addressdetails: 1, limit: 5 },
                        headers: { 'Accept-Language': 'en' },
                        success: function(res) {
                            renderSuggestions(res);
                        },
                        error: function() {
                            closeSuggestions();
                        }
                    });
                }, 300);
            });

            function detectGoogleMapsCoordinates(text) {
                // Match @lat,lng in Google Maps URL
                try {
                    if (/maps.app.goo.gl/.test(text)) {
                        alert('Short Google Maps links (maps.app.goo.gl) cannot be resolved automatically. Please open the short link in your browser, copy the full URL (which contains @latitude,longitude) and paste it here.');
                        return null;
                    }
                    const atMatch = text.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                    if (atMatch) {
                        return { lat: atMatch[1], lon: atMatch[2], clean: null };
                    }

                    // Match /place/PLACE_NAME
                    const placeMatch = text.match(/\/place\/([^\/]+)/);
                    if (placeMatch) {
                        const name = decodeURIComponent(placeMatch[1].replace(/\+/g, ' '));
                        // Perform a quick nominatim search synchronously via ajax to get coords
                        var result = null;
                        $.ajax({
                            url: 'https://nominatim.openstreetmap.org/search',
                            data: { q: name, format: 'json', limit: 1 },
                            async: false,
                            success: function(res) {
                                if (res && res.length > 0) {
                                    result = { lat: res[0].lat, lon: res[0].lon, clean: res[0].display_name };
                                }
                            }
                        });
                        return result;
                    }

                    // If the text looks like coordinates plain 'lat, lng'
                    const plainCoords = text.match(/(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);
                    if (plainCoords) {
                        return { lat: plainCoords[1], lon: plainCoords[2], clean: null };
                    }
                } catch (e) {
                    console.error('detectGoogleMapsCoordinates error', e);
                }
                return null;
            }

            // close when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#street_address, #address-suggestions').length) {
                    closeSuggestions();
                }
            });
        })();

        // --- Business Hours Toggling Logic --- 
        
        // Initialize checkboxes on page load
        $('.form-check-input[id$="_closed"]').each(function() {
            const day = this.id.replace('_closed', '');
            toggleTimeInputs(this, day); // Call toggle on load to set initial state
            
            // Attach change listener using jQuery's .on()
            $(this).on('change', function() {
                toggleTimeInputs(this, day);
            });
        });
    });
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.user_type.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\YCcenter\resources\views/vendor/business/create.blade.php ENDPATH**/ ?>