{{-- Server-side error under a registration field; same markup jQuery Validate uses, so it is cleared once the field is fixed --}}
@if ($errors->has($field))
    <label id="{{ $field }}-error" class="error server-error" for="{{ $field }}">{{ $errors->first($field) }}</label>
@endif
