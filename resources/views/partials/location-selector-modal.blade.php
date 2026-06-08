<!-- Location Selector Modal -->
<div class="modal fade" id="locationSelectorModal" tabindex="-1" role="dialog" aria-labelledby="locationSelectorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" id="modalCloseBtn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" style="color: #fff;">
          <i class="fa-solid fa-location-dot"></i> Select Your Location
        </h4>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3" style="display: none;">
          <span class="text-muted">─── OR ───</span>
        </div>

        <!-- Auto Detect Section -->
        <div style="text-align: center; margin-bottom: 25px;">
          <button type="button" id="autoDetectBtn" style="background: var(--primary-color); color: white; border: none; padding: 12px 30px; border-radius: 25px; font-weight: 500; font-size: 14px; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(10, 15, 31, 0.3);">
            <i class="fa-solid fa-crosshairs" style="margin-right: 8px;"></i>
            <span id="autoDetectText" style="color: #fff;">Auto-Detect My Location</span>
          </button>
          <div id="httpsWarning" style="display: none; margin-top: 10px; padding: 8px 12px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px; color: var(--primary-darker); font-size: 12px;">
            <i class="fa-solid fa-exclamation-triangle" style="margin-right: 5px;"></i>
            Location detection requires HTTPS. Please contact the site administrator.
          </div>

          <!-- Development mode quick location selector -->
          <div id="devModeSelector" style="display: none; margin-top: 15px; padding: 12px; background: #e8f4f8; border: 1px solid #bee5eb; border-radius: 8px;">
            <h6 style="color: #0c5460; margin-bottom: 10px; font-size: 13px;">
              <i class="fa-solid fa-code" style="margin-right: 5px;"></i> Quick Location (Development Mode)
            </h6>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
              <button type="button" class="btn btn-sm btn-outline-info" onclick="setQuickLocation('Mumbai', '400001')">
                <i class="fa-solid fa-map-marker-alt"></i> Mumbai
              </button>
              <button type="button" class="btn btn-sm btn-outline-info" onclick="setQuickLocation('Delhi', '110001')">
                <i class="fa-solid fa-map-marker-alt"></i> Delhi
              </button>
              <button type="button" class="btn btn-sm btn-outline-info" onclick="setQuickLocation('Pune', '411001')">
                <i class="fa-solid fa-map-marker-alt"></i> Pune
              </button>
              <button type="button" class="btn btn-sm btn-outline-info" onclick="setQuickLocation('Bangalore', '560001')">
                <i class="fa-solid fa-map-marker-alt"></i> Bangalore
              </button>
            </div>
            <small style="color: #6c757d; margin-top: 8px; display: block;">
              Quick location selection for testing. Use manual selection below for precise location.
            </small>
          </div>
          <div id="detectionInfo" style="display: none; margin-top: 10px; padding: 8px 12px; background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 6px; color: #1565c0; font-size: 12px;">
            <i class="fa-solid fa-info-circle" style="margin-right: 5px;"></i>
            <span id="detectionInfoText">Detecting zipcode and area...</span>
          </div>
        </div>

        <!-- Manual Selection Section - Hidden -->
        <div class="location-manual-select" style="display: none;">
          <h6 class="mb-3">
            <i class="fa-solid fa-hand-pointer"></i> Select Manually:
            <span id="manualSelectNote" style="display: none; font-size: 12px; color: var(--primary-color); font-weight: normal;">
              (Recommended when auto-detection is unavailable)
            </span>
          </h6>

          <form id="locationForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <!-- State Dropdown -->
            <div class="form-group">
              <label for="stateSelect" class="form-label">
                <i class="fa-solid fa-map"></i> State
              </label>
              <select class="form-control" id="stateSelect" required>
                <option value="">Loading states...</option>
              </select>
            </div>

            <!-- City Dropdown -->
            <div class="form-group">
              <label for="citySelect" class="form-label">
                <i class="fa-solid fa-building"></i> City
              </label>
              <select class="form-control" id="citySelect" disabled required>
                <option value="">Select state first</option>
              </select>
            </div>

            <!-- Area Dropdown -->
            <div class="form-group">
              <label for="areaSelect" class="form-label">
                <i class="fa-solid fa-map-pin"></i> Area
              </label>
              <select class="form-control" id="areaSelect" disabled required>
                <option value="">Select city first</option>
              </select>
            </div>

            <button type="submit" class="btn btn-success btn-block">
              <i class="fa-solid fa-check"></i> Set Location
            </button>
          </form>
        </div>

        <!-- Loading State -->
        <div class="location-loading text-center" style="display: none;">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
          <p class="mt-2 mb-0">
            <span class="loading-text">Detecting your location...</span>
          </p>
        </div>

        <!-- Error State -->
        <div class="location-error" style="display: none;">
          <div class="alert alert-warning">
            <i class="fa-solid fa-exclamation-triangle"></i>
            <span class="error-text">Unable to detect location. Please try again later.</span>
          </div>
        </div>

        <!-- Success State -->
        <div class="location-success" style="display: none;">
          <div class="alert alert-success">
            <i class="fa-solid fa-check-circle"></i>
            <span class="success-text">Location set successfully!</span>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="skipForNowBtn">
          Skip for Now
        </button>
      </div>
    </div>
  </div>
</div>

<style>
/* Location Modal Styles */
#locationSelectorModal .modal-dialog {
  max-width: 450px;
}

#locationSelectorModal .modal-header {
  background: linear-gradient(45deg, var(--primary-color), #0a0f1f);
  color: white;
  border-bottom: none;
}

#locationSelectorModal .modal-header .close {
  color: white;
  opacity: 0.8;
}

#locationSelectorModal .modal-header .close:hover {
  opacity: 1;
}

#locationSelectorModal .btn-primary {
  background: linear-gradient(45deg, var(--primary-color), var(--primary-lighter));
  border: none;
  color: white;
  padding: 12px 20px;
  font-weight: 500;
  transition: all 0.3s ease;
}

#locationSelectorModal .btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(10, 15, 31, 0.3);
}

#locationSelectorModal .btn-success {
  background: linear-gradient(45deg, var(--primary-color), var(--primary-lighter));
  border: none;
  padding: 10px 20px;
  font-weight: 500;
}

#locationSelectorModal .form-control {
  border: 2px solid #e9ecef;
  border-radius: 8px;
  padding: 0px 15px;
  transition: border-color 0.3s ease;
}

#locationSelectorModal .form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 0.2rem rgba(10, 15, 31, 0.25);
}

#locationSelectorModal .form-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 5px;
}

#locationSelectorModal .form-label i {
  margin-right: 5px;
  color: var(--primary-color);
}

#locationSelectorModal .alert {
  border: none;
  border-radius: 8px;
  padding: 12px 15px;
}

.spinner-border {
  width: 2rem;
  height: 2rem;
}

/* Auto-detect button states */
#autoDetectBtn.detecting {
  background: linear-gradient(45deg, #6c757d, #8e9298);
  cursor: not-allowed;
}

#autoDetectBtn.success {
  background: linear-gradient(45deg, #28a745, #34ce57);
}

/* Enhanced detection info styling */
#detectionInfo {
  display: none;
  margin-top: 10px;
  padding: 12px 15px;
  background: #e3f2fd;
  border: 1px solid #bbdefb;
  border-radius: 8px;
  color: #1565c0;
  font-size: 12px;
  line-height: 1.4;
}

#detectionInfo.success {
  background: #e8f5e8;
  border-color: #c3e6c3;
  color: #2e7d32;
}

#detectionInfo strong {
  font-size: 13px;
  display: block;
  margin-bottom: 4px;
}

#detectionInfo small {
  display: block;
  margin-bottom: 2px;
  opacity: 0.8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = $('#locationSelectorModal');
    const autoDetectBtn = document.getElementById('autoDetectBtn');
    const autoDetectText = document.getElementById('autoDetectText');
    const httpsWarning = document.getElementById('httpsWarning');
    const detectionInfo = document.getElementById('detectionInfo');
    const detectionInfoText = document.getElementById('detectionInfoText');
    const locationForm = document.getElementById('locationForm');
    const stateSelect = document.getElementById('stateSelect');
    const citySelect = document.getElementById('citySelect');
    const areaSelect = document.getElementById('areaSelect');
    const manualSelectNote = document.getElementById('manualSelectNote');
    const devModeSelector = document.getElementById('devModeSelector');
    const skipForNowBtn = document.getElementById('skipForNowBtn');
    const modalCloseBtn = document.getElementById('modalCloseBtn');

    let isDetecting = false;
    let closedViaSkip = false; // Track how modal was closed

    // Session storage keys
    const LOCATION_SKIPPED_KEY = 'location_modal_skipped';
    const LOCATION_DISMISSED_KEY = 'location_modal_dismissed';

    // Check if modal should be shown
    function shouldShowLocationModal() {
        // Don't show if user clicked "Skip for Now" in this session
        if (sessionStorage.getItem(LOCATION_SKIPPED_KEY) === 'true') {
            return false;
        }

        // Don't show if user already has location set
        if (sessionStorage.getItem('user_location')) {
            return false;
        }

        return true;
    }

    // Handle Skip for Now button
    if (skipForNowBtn) {
        skipForNowBtn.addEventListener('click', function() {
            closedViaSkip = true;
            // Set session flag to prevent modal from showing again
            sessionStorage.setItem(LOCATION_SKIPPED_KEY, 'true');
            modal.modal('hide');
        });
    }

    // Handle X (close) button - allow modal to appear again
    if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', function() {
            closedViaSkip = false;
            // Remove skip flag so modal can appear again
            sessionStorage.removeItem(LOCATION_SKIPPED_KEY);
        });
    }

    // Handle modal hidden event
    modal.on('hidden.bs.modal', function(e) {
        // Reset the flag after modal is hidden
        setTimeout(() => {
            closedViaSkip = false;
        }, 100);
    });

    // Override the default modal show behavior for automatic popups
    window.showLocationModal = function() {
        if (shouldShowLocationModal()) {
            modal.modal('show');
        }
    };

    // New function for manual trigger from navbar - *always* shows the modal
    window.forceShowLocationModal = function() {
        // Remove the skipped flag in case user wants to re-enable auto-popups by closing with 'X'
        sessionStorage.removeItem(LOCATION_SKIPPED_KEY);
        modal.modal('show');
    };

    // Check HTTPS for geolocation
    function checkGeolocationSupport() {
        const isHTTPS = window.location.protocol === 'https:';
        const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
        const hasGeolocation = 'geolocation' in navigator;

        // Geolocation works on HTTPS or localhost
        if ((!isHTTPS && !isLocalhost) || !hasGeolocation) {
            let warningMessage = '';
            if (!isHTTPS && !isLocalhost) {
                warningMessage = 'Location detection requires HTTPS. Please contact the site administrator.';
            } else if (!hasGeolocation) {
                warningMessage = 'Your browser does not support location detection. Please contact the site administrator.';
            }

            httpsWarning.style.display = 'block';
            httpsWarning.innerHTML = `
                <i class="fa-solid fa-exclamation-triangle" style="margin-right: 5px;"></i>
                ${warningMessage}
            `;
            autoDetectBtn.disabled = true;
            autoDetectBtn.style.opacity = '0.6';
            autoDetectBtn.style.cursor = 'not-allowed';
            autoDetectText.textContent = 'Auto-Detection Unavailable';

            // Show manual selection note
            if (manualSelectNote) {
                manualSelectNote.style.display = 'inline';
            }

            // Show development mode quick selector for non-HTTPS environments
            if (!isHTTPS && !isLocalhost && devModeSelector) {
                devModeSelector.style.display = 'block';
            }

            // Add explanation for HTTPS requirement
            if (!isHTTPS && !isLocalhost) {
                const httpsExplanation = document.createElement('div');
                httpsExplanation.style.cssText = 'margin-top: 8px; padding: 8px 12px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; color: #6c757d; font-size: 11px;';
                httpsExplanation.innerHTML = `
                    <strong>Why HTTPS?</strong> Modern browsers require secure connections for location access to protect user privacy.
                `;
                httpsWarning.appendChild(httpsExplanation);
            }

            return false;
        }

        // Hide warning if conditions are met
        httpsWarning.style.display = 'none';
        autoDetectBtn.disabled = false;
        autoDetectBtn.style.opacity = '1';
        autoDetectBtn.style.cursor = 'pointer';
        autoDetectText.textContent = 'Auto-Detect My Location';

        // Hide manual selection note
        if (manualSelectNote) {
            manualSelectNote.style.display = 'none';
        }

        // Hide development mode selector
        if (devModeSelector) {
            devModeSelector.style.display = 'none';
        }

        return true;
    }

    // Load states on modal open
    modal.on('shown.bs.modal', function() {
        loadStates();
        checkGeolocationSupport();
    });

    // Auto-detect location
    autoDetectBtn.addEventListener('click', function() {
        if (isDetecting) return;

        if (!checkGeolocationSupport()) {
            const isHTTPS = window.location.protocol === 'https:';
            const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

            if (!isHTTPS && !isLocalhost) {
                showError('Location detection requires HTTPS. Please contact the site administrator.');
            } else {
                showError('Location detection not available. Please contact the site administrator.');
            }

            // Scroll to manual selection
            document.querySelector('.location-manual-select').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            return;
        }

        startDetection();

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                const accuracy = position.coords.accuracy;

                detectionInfoText.textContent = 'Found coordinates (' + latitude.toFixed(6) + ', ' + longitude.toFixed(6) + '), getting address...';

                // Send coordinates to server for zipcode detection
                fetch('/location/auto-detect', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        latitude: latitude,
                        longitude: longitude,
                        accuracy: accuracy
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let displayText = `Location set: ${data.display_name}`;
                        if (data.coordinates) {
                            displayText += ` ${data.coordinates}`;
                        }
                        if (data.zipcode) {
                            displayText += ` (${data.zipcode})`;
                        }

                        detectionInfoText.innerHTML = `
                            <strong>✓ Location Detected</strong><br>
                            <small>${data.full_address || data.display_name}</small><br>
                            <small>Coordinates: ${data.coordinates || 'N/A'}</small>
                            ${data.zipcode ? `<br><small>Zipcode: ${data.zipcode}</small>` : ''}
                        `;

                        // Add success styling to detection info
                        detectionInfo.classList.add('success');

                        showSuccess('Location auto-detected successfully!');
                        // Update the main detection info box with a simpler message
                        if (detectionInfo && detectionInfoText) {
                            detectionInfoText.innerHTML = '✓ Location detected. Sorting businesses based on your current location.';
                            detectionInfo.className = 'success'; // Assuming a CSS class for success styling
                            detectionInfo.style.display = 'block';
                        }

                        // Auto-close modal after a short delay
                        setTimeout(() => {
                            modal.modal('hide');
                            updateNavbarLocation(data.navbar_display_name || data.display_name);
                            // Reload page to reflect new location-based sorting
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Location detection failed');
                    }
                })
                .catch(error => {
                    console.error('Auto-detection error:', error);
                    showError(error.message || 'Failed to detect location. Please try manual selection.');
                })
                .finally(() => {
                    stopDetection();
                });
            },
            function(error) {
                let errorMessage = 'Location access denied. ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'Please allow location access and try again.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'Location information unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'Location request timed out.';
                        break;
                    default:
                        errorMessage += 'Unknown error occurred.';
                        break;
                }
                showError(errorMessage);
                stopDetection();
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 60000
            }
        );
    });

    // Load states
    function loadStates() {
        fetch('/location/states')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    stateSelect.innerHTML = '<option value="">Select State</option>';
                    data.states.forEach(state => {
                        stateSelect.innerHTML += `<option value="${state.id}">${state.name}</option>`;
                    });
                } else {
                    stateSelect.innerHTML = '<option value="">Error loading states</option>';
                }
            })
            .catch(error => {
                console.error('Error loading states:', error);
                stateSelect.innerHTML = '<option value="">Error loading states</option>';
            });
    }

    // State change handler
    stateSelect.addEventListener('change', function() {
        const stateId = this.value;
        citySelect.innerHTML = '<option value="">Loading cities...</option>';
        citySelect.disabled = true;
        areaSelect.innerHTML = '<option value="">Select city first</option>';
        areaSelect.disabled = true;

        if (stateId) {
            fetch(`/location/states/${stateId}/cities`)
                .then(response => response.json())
                .then(cities => {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    cities.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                    citySelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading cities:', error);
                    citySelect.innerHTML = '<option value="">Error loading cities</option>';
                });
        }
    });

    // City change handler
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        areaSelect.innerHTML = '<option value="">Loading areas...</option>';
        areaSelect.disabled = true;

        if (cityId) {
            fetch(`/location/cities/${cityId}/areas`)
                .then(response => response.json())
                .then(areas => {
                    areaSelect.innerHTML = '<option value="">Select Area</option>';
                    areas.forEach(area => {
                        areaSelect.innerHTML += `<option value="${area.id}">${area.name}</option>`;
                    });
                    areaSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading areas:', error);
                    areaSelect.innerHTML = '<option value="">Error loading areas</option>';
                });
        }
    });

    // Manual location form submission
    locationForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            state_id: stateSelect.value,
            city_id: citySelect.value,
            area_id: areaSelect.value,
            type: 'manual'
        };

        if (!formData.state_id || !formData.city_id || !formData.area_id) {
            showError('Please select all location fields.');
            return;
        }

        showLoading('Setting your location...');

        fetch('/location/set', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                setTimeout(() => {
                    modal.modal('hide');
                    updateNavbarLocation(data.navbar_display_name || data.display_name);
                    // Reload page to reflect new location-based sorting
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to set location');
            }
        })
        .catch(error => {
            console.error('Manual location error:', error);
            showError(error.message || 'Failed to set location. Please try again.');
        })
        .finally(() => {
            hideLoading();
        });
    });

    // Helper functions
    function startDetection() {
        isDetecting = true;
        autoDetectBtn.className = 'detecting';
        autoDetectBtn.disabled = true;
        autoDetectText.textContent = 'Detecting...';
        detectionInfo.style.display = 'block';
        detectionInfo.classList.remove('success');
        detectionInfoText.textContent = 'Getting your coordinates...';
        hideMessages();
    }

    function stopDetection() {
        isDetecting = false;
        autoDetectBtn.className = '';
        autoDetectBtn.disabled = false;
        autoDetectText.textContent = 'Auto-Detect My Location';
        autoDetectBtn.style.background = 'var(--primary-color)';
        setTimeout(() => {
            detectionInfo.style.display = 'none';
        }, 3000);
    }

    function showLoading(text) {
        hideMessages();
        document.querySelector('.location-loading').style.display = 'block';
        document.querySelector('.loading-text').textContent = text;
    }

    function hideLoading() {
        document.querySelector('.location-loading').style.display = 'none';
    }

    function showError(message) {
        hideMessages();
        const errorDiv = document.querySelector('.location-error');
        const errorText = document.querySelector('.error-text');
        errorText.textContent = message;
        errorDiv.style.display = 'block';
        setTimeout(() => errorDiv.style.display = 'none', 5000);
    }

    function showSuccess(message) {
        hideMessages();
        const successDiv = document.querySelector('.location-success');
        const successText = document.querySelector('.success-text');
        successText.textContent = message;
        successDiv.style.display = 'block';
        autoDetectBtn.className = 'success';
        autoDetectText.textContent = 'Location Set!';
    }

    function hideMessages() {
        document.querySelector('.location-loading').style.display = 'none';
        document.querySelector('.location-error').style.display = 'none';
        document.querySelector('.location-success').style.display = 'none';
    }

    function updateNavbarLocation(displayName) {
        // Update navbar location display if element exists
        const navLocationElement = document.querySelector('.current-location');
        if (navLocationElement) {
            navLocationElement.textContent = displayName;
        }

        // Update any other location displays on the page
        const locationDisplays = document.querySelectorAll('[data-location-display]');
        locationDisplays.forEach(element => {
            element.textContent = displayName;
        });
    }

    // Quick location setter for development mode
    window.setQuickLocation = function(cityName, zipcode) {
        showLoading(`Setting location to ${cityName}...`);

        // Create a mock location data for quick testing
        const locationData = {
            display_name: `${cityName}, India`,
            zipcode: zipcode,
            type: 'quick_select',
            city_name: cityName
        };

        // Simulate setting location
        fetch('/location/set', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                quick_location: true,
                city_name: cityName,
                zipcode: zipcode,
                type: 'quick_select'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(`Location set to ${cityName}!`);
                setTimeout(() => {
                    modal.modal('hide');
                    updateNavbarLocation(data.navbar_display_name || data.display_name || `${cityName}, India`);
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to set location');
            }
        })
        .catch(error => {
            console.error('Quick location error:', error);
            showError('Failed to set location. Please try manual selection.');
        })
        .finally(() => {
            hideLoading();
        });
    };
});
</script>
