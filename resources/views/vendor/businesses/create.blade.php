                        </div>
                        
                        <h6 class="text-uppercase text-body text-xs font-weight-bolder mt-4 mb-3">Business Hours</h6>
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                        <div class="row border-bottom py-2 mb-2">
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="{{ $day }}_open" class="form-control-label text-capitalize mb-0">{{ $day }}</label>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="time" id="{{ $day }}_open" name="{{ $day }}_open" value="{{ old($day.'_open', '09:00') }}">
                            </div>
                             <div class="col-md-1 d-flex align-items-center justify-content-center">
                                <span class="text-sm">to</span>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="time" id="{{ $day }}_close" name="{{ $day }}_close" value="{{ old($day.'_close', '17:00') }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <div class="form-check form-switch ps-0">
                                  <input class="form-check-input ms-auto" type="checkbox" id="{{ $day }}_closed" name="{{ $day }}_closed" value="1" {{ old($day.'_closed') ? 'checked' : '' }}>
                                  <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="{{ $day }}_closed">Closed</label>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('vendor.businesses.index') }}" class="btn btn-light m-0">Cancel</a> 
 
 
                        
                        <h6 class="text-uppercase text-body text-xs font-weight-bolder mt-4 mb-3">Business Hours</h6>
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                        <div class="row border-bottom py-2 mb-2">
                            <div class="col-md-2 d-flex align-items-center">
                                <label for="{{ $day }}_open" class="form-control-label text-capitalize mb-0">{{ $day }}</label>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="time" id="{{ $day }}_open" name="{{ $day }}_open" value="{{ old($day.'_open', '09:00') }}">
                            </div>
                             <div class="col-md-1 d-flex align-items-center justify-content-center">
                                <span class="text-sm">to</span>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="time" id="{{ $day }}_close" name="{{ $day }}_close" value="{{ old($day.'_close', '17:00') }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <div class="form-check form-switch ps-0">
                                  <input class="form-check-input ms-auto" type="checkbox" id="{{ $day }}_closed" name="{{ $day }}_closed" value="1" {{ old($day.'_closed') ? 'checked' : '' }}>
                                  <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="{{ $day }}_closed">Closed</label>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('vendor.businesses.index') }}" class="btn btn-light m-0">Cancel</a> 
 