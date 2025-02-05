
<form action="{{ route('pending.kyc.update', $customer->kycData->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row gy-4">
        <div class="col-md-6">
            <label class="form-label" for="name">Name</label>
            <input type="text" class="form-control" value="{{ $customer->name }}" disabled>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="ref_code">Ref Code</label>
            <input type="text" class="form-control"
                placeholder="Ref code" value="{{ $customer->ref_code }}"
                disabled>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="bvn">BVN 
                @if(isset($kycDataStatuses->bvn))
                <span class="badge" style="background-color: {{ $kycDataStatuses->bvn->status == 'verified' ? '#28a745' : '#dc3545' }}; color: #fff; padding: 5px 10px; border-radius: 5px; font-size: 12px;">
                    {{ ucfirst($kycDataStatuses->bvn->status) }} 
                </span><span class="verified-by"> by: {{ $kycDataStatuses->bvn->method }}</span>
                @else
                <span class="badge" style="background-color:#dc3545; color: #fff; padding: 5px 10px; border-radius: 5px; font-size: 12px;">Unverified</span>
                @endif
        </label>
            <input name="bvn" type="text" class="form-control" placeholder="BVN" value="{{ in_array('view.sensitive.data', allowedPermissions()) ? $customer->kycData->bvn : mask($customer->kycData->bvn) }}" {{ !in_array('view.sensitive.data', allowedPermissions()) ? 'readonly' : '' }}>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="nin">NIN
                @if(isset($kycDataStatuses->nin))
                <span class="badge" style="background-color: {{ $kycDataStatuses->nin->status == 'verified' ? '#28a745' : '#dc3545' }}; color: #fff; padding: 5px 10px; border-radius: 5px; font-size: 12px;">
                    {{ ucfirst($kycDataStatuses->nin->status) }} 
                </span><span class="verified-by"> by: {{ $kycDataStatuses->nin->method }}</span>
                @else
                <span class="badge" style="background-color:#dc3545; color: #fff; padding: 5px 10px; border-radius: 5px; font-size: 12px;">Unverified</span>
                @endif
            </label>
            <input type="text" name="nin" class="form-control"
                placeholder="NIN" value="{{ in_array('view.sensitive.data', allowedPermissions()) ? $customer->kycData->nin : mask($customer->kycData->nin) }}" {{ !in_array('view.sensitive.data', allowedPermissions()) ? 'readonly' : '' }}>
        </div>
        
        <div class="col-md-6">
            <label class="form-label" for="gender">Means of Identification Type</label>
            <select class="form-control" name="means_of_identification_type" id="means_of_identification_type" required>
                <option value="">Select</option>
                <option value="international_passport" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'international_passport' ? 'selected' : '' }}>International Passport</option>
                <option value="drivers_license" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                <option value="electricity_bill" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'electricity_bill' ? 'selected' : '' }}>Electricity Bill</option>
                <option value="nin_slip" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'nin_slip' ? 'selected' : '' }}>NIN Slip</option>
                <option value="voters_card" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'voters_card' ? 'selected' : '' }}>Voter's Card</option>
            </select>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-2">
                    <img style="width: 70px;" src="{{asset($customer->kycData->means_of_identification_document)}}" alt="">
                </div>
                <div class="col-md-10">
                    <label class="form-label">Means of Identification Document (JPEG, JPG Only, 1mb max)</label>
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" name="means_of_identification_document" accept=".jpeg, .jpg, image/jpeg" id="means_of_identification_document">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button type="Save" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>