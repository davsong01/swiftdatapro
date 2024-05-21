<div class="modal fade" id="primary" tabindex="-1" aria-labelledby="exampleModalXlLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title white" id="myModalLabel160">Add Variations for {{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('manual.variations.add', $product->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <section id="mode-holder">
                        <div class="row gy-4" id="mode-0">
                            <div class="col-md-3">
                                <label class="form-label" for="name">System Name</label>
                                <input type="text" class="form-control" id="system_name" name="system_name[]"  value="" placeholder="Variation name" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="name">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug[]"  value="" placeholder="Variation slug" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="fixed_price">Fixed Price</label>
                                <select class="form-control" name="fixed_price[]" id="fixed_price" required>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('fixed_price') == 'Yes' ? 'selected' : ''}}>Yes</option>
                                    <option value="No" {{ old('fixed_price') == 'No' ? 'selected' : ''}}>No</option> 
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-control" name="status[]" id="status" required>
                                    <option value="">Select</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : ''}}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : ''}}>InActive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="min">Min Amount</label>
                                <input type="number" class="form-control" id="min" name="min[]"  value="{{ old('min') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="max">Max Amount</label>
                                <input type="number" class="form-control" id="max" name="max[]"  value="{{ old('max') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="name">API Price ({!! settings()['currency']!!})</label>
                                <input type="number" class="form-control" id="api_price" name="api_price[]"  value="" placeholder="API price" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="name">System Price ({!! settings()['currency']!!})</label>
                                <input type="number" class="form-control" id="system_price" name="system_price[]"  value="" placeholder="Variation price" required>
                            </div>
                            @foreach($customerlevel as $level)
                            <div class="col-md-3">
                                <label class="form-label" for="name">{{ $level->name }} @if($product->category->discount_type == 'flat') Discounted Price ({!! settings()['currency']!!}) @else Discounted Percentage (%) @endif</label>
                                <input type="number" class="form-control" id="level" step=".01" name="level[{{ $level->id }}][]" value="" required>
                            </div>
                            @endforeach
                            <div class="col-md-2">
                                <label class="form-label" style="color:white">S</label>
                                <button style="color: white;" class="btn btn-success form-control" style="padding: 8px;" type="button" id="add-mode"><i class="bx bx-plus"></i> Add More</button>
                            </div>
                            <div class="col-md-12">
                            <hr style="height: 0px;border-color: #00cfdd;">
                            </div>
                        </div>
                    </section>
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="submit" class="btn btn-primary ml-1"><span class="d-none d-sm-block">Submit</span>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>