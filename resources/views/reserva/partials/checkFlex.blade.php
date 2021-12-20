<div class="card">
    <div class="card-body">
        <input class="form-check-input me-1" type="{{ $type ?? '' }}" name="{{ $name ?? '' }}" id="id{{ $label }}" value="{{ $value ?? '' }}" @if(old('repeat_days.'.$value) == $value)  checked  @endif><label for="id{{ $label ?? '' }}">{{ $label ?? '' }}</label>
     </div>
</div>
