@if (is_numeric($value))
    R$ {{ number_format((float) $value, 2, ',', '.') }}
@else
    {{ $value ?? '-' }}
@endif
