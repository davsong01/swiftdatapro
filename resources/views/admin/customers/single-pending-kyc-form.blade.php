<?php
    $kycDataStatuses = kycDataStatus($customer->id);  
?>
<form action="{{ route('pending.kyc.update', $customer->kycData->id) }}" method="POST" enctype="multipart/form-data">
    @csrf 
    <div class="row">
        <!-- Left Column (Form) -->
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label mb-2" for="name">Name</label>
                    <input type="text" class="form-control" value="{{ $customer->name }}" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label mb-2" for="ref_code">Ref Code</label>
                    <input type="text" class="form-control" placeholder="Ref code" value="{{ $customer->ref_code }}" disabled>
                </div>
                
               
                <div class="col-md-6 mb-3">
                    <label class="form-label mb-2" for="bvn">BVN</label>
                    <div class="d-flex align-items-center">
                        {{-- {{dd($kycDataStatuses) }} --}}
                        @if(!empty($customer->kycData->bvn))
                            @if(isset($kycDataStatuses->bvn))
                                <span class="badge me-2" style="background-color: {{ $kycDataStatuses->bvn->status == 'verified' ? '#28a745' : '#dc3545' }}; color: #fff;">
                                    {{ ucfirst($kycDataStatuses->bvn->status) }} 
                                </span>
                                <span class="verified-by">by: {{ $kycDataStatuses->bvn->method }}</span>
                            @else
                                <span class="badge me-2" style="background-color:#dc3545; color: #fff;">Unverified</span>
                                <a href="{{ route('manual-verify-kyc', ['id' => $customer->kycData->id, 'customer_id' => $customer->id, 'type' => 'bvn', 'value' => $customer->kycData->bvn]) }}" class="btn btn-light btn-sm">
                                    <i class="fa fa-check"></i> Verify
                                </a>
                            @endif
                        @endif
                    </div>
                    <input type="text" name="bvn" class="form-control mt-2" placeholder="BVN" value="{{ in_array('view.sensitive.data', allowedPermissions()) ? $customer->kycData->bvn : mask($customer->kycData->bvn) }}" {{ !in_array('view.sensitive.data', allowedPermissions()) ? 'readonly' : '' }}>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label mb-2" for="nin">NIN</label>
                    <div class="d-flex align-items-center">
                        @if(!empty($customer->kycData->nin))
                            @if(isset($kycDataStatuses->nin))
                                <span class="badge me-2" style="background-color: {{ $kycDataStatuses->nin->status == 'verified' ? '#28a745' : '#dc3545' }}; color: #fff;">
                                    {{ ucfirst($kycDataStatuses->nin->status) }}
                                </span>
                                <span class="verified-by">by: {{ $kycDataStatuses->nin->method }}</span>
                            @else
                                <span class="badge me-2" style="background-color:#dc3545; color: #fff;">Unverified</span>
                                <a href="{{ route('manual-verify-kyc', ['id' => $customer->kycData->id, 'customer_id' => $customer->id, 'type' => 'nin', 'value' => $customer->kycData->nin]) }}" class="btn btn-light btn-sm">
                                    <i class="fa fa-check"></i> Verify
                                </a>
                            @endif
                        @endif
                    </div>
                    <input type="text" name="nin" class="form-control mt-2" placeholder="NIN" value="{{ in_array('view.sensitive.data', allowedPermissions()) ? $customer->kycData->nin : mask($customer->kycData->nin) }}" {{ !in_array('view.sensitive.data', allowedPermissions()) ? 'readonly' : '' }}>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label mb-2">Means of Identification Type</label>
                    <select class="form-control" name="means_of_identification_type" required>
                        <option value="">Select</option>
                        <option value="international_passport" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'international_passport' ? 'selected' : '' }}>International Passport</option>
                        <option value="drivers_license" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                        <option value="electricity_bill" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'electricity_bill' ? 'selected' : '' }}>Electricity Bill</option>
                        <option value="nin_slip" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'nin_slip' ? 'selected' : '' }}>NIN Slip</option>
                        <option value="voters_card" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'voters_card' ? 'selected' : '' }}>Voter's Card</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img style="width: 50px;" src="{{ asset($customer->kycData->means_of_identification_document) }}" alt="">
                        </div>
                        <div class="col-md-10">
                            <label style="font-size:9px" class="form-label mb-2">Means of Identification Document (JPEG, JPG Only, 1MB max)</label>
                            <input type="file" class="form-control" name="means_of_identification_document" accept=".jpeg, .jpg, image/jpeg">
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card p-3">
                <h5 class="mb-3">Verification Data</h5>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="kycTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="bvn-tab" data-bs-toggle="tab" data-bs-target="#bvn" type="button" role="tab">
                            BVN
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="nin-tab" data-bs-toggle="tab" data-bs-target="#nin" type="button" role="tab">
                            NIN
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                
                <div class="tab-content mt-3" id="kycTabContent">
                    <!-- BVN Verification Tab -->
                    <div class="tab-pane fade show active" id="bvn" role="tabpanel">
                        <p><strong>Status:</strong> {{ $kycDataStatuses->bvn->status ?? 'Unverified' }}</p>
                        <p><strong>Verified By:</strong> {{ $kycDataStatuses->bvn->method ?? 'N/A' }}</p>
                        <p><strong>Last Updated:</strong> {{ $customer->kycData->updated_at->format('d M Y, H:i A') }}</p>
                        <p>
                            @if(!empty($kycDataStatuses->bvn->raw_data))
                                @php
                                    $excludedKeys = [];
                                @endphp

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Key</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kycDataStatuses->bvn->raw_data as $key => $value)
                                                @if(!in_array($key, $excludedKeys) && $value !== '****')
                                                    <tr>
                                                        <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                        <td>
                                                            @if($key == 'photo' || $key == 'signature')
                                                                <img src="data:image/jpeg;base64,{{ is_string($value) ? $value : json_encode($value) }}" height="50" onclick="zoomImg(this)" style="cursor:zoom-in"/>
                                                            @else
                                                                {{-- Convert stdClass to an array and encode it properly --}}
                                                                {{ is_array($value) || is_object($value) ? json_encode((array) $value) : $value }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </p>
                    </div>

                    <!-- NIN Verification Tab -->
                    <div class="tab-pane fade" id="nin" role="tabpanel">
                        <p><strong>Status:</strong> {{ $kycDataStatuses->nin->status ?? 'Unverified' }}</p>
                        <p><strong>Verified By:</strong> {{ $kycDataStatuses->nin->method ?? 'N/A' }}</p>
                        <p><strong>Last Updated:</strong> {{ $customer->kycData->updated_at->format('d M Y, H:i A') }}</p>
                        <p>
                            @if(!empty($kycDataStatuses->nin->raw_data))
                                @php
                                    // Define keys to exclude
                                    $excludedKeys = [];
                                @endphp

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Key</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kycDataStatuses->nin->raw_data as $key => $value)
                                                @if(!in_array($key, $excludedKeys) && $value !== '****')
                                                    <tr>
                                                        <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                        <td>
                                                            @if($key == 'photo' || $key == 'signature')
                                                                <img src="data:image/jpeg;base64,{{ $value }}" height="50" onclick="zoomImg(this)" style="cursor:zoom-in"/>
                                                            @else
                                                                {{ is_array($value) ? json_encode($value) : $value }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- Zoom Modal -->
                                    <div id="imgModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); justify-content: center; align-items: center; z-index: 1000;">
                                        <span id="closeModal" style="position: absolute; top: 20px; right: 30px; font-size: 30px; color: white; cursor: pointer;">&times;</span>
                                        <img id="zoomedImg" style="max-width: 90%; max-height: 90%; border-radius: 10px;">
                                    </div>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            var modal = document.getElementById("imgModal");
                                            var zoomedImg = document.getElementById("zoomedImg");
                                            var closeModal = document.getElementById("closeModal");

                                            window.zoomImg = function(img) {
                                                zoomedImg.src = img.src;
                                                modal.style.display = "flex";
                                            };

                                            // Close modal when clicking the close button
                                            closeModal.onclick = function() {
                                                modal.style.display = "none";
                                            };

                                            // Close modal when clicking anywhere outside the image
                                            modal.onclick = function(event) {
                                                if (event.target === modal) {
                                                    modal.style.display = "none";
                                                }
                                            };
                                        });
                                    </script>
                                </div>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>