@extends('admin-template.dashboard')

@section('content')
<div id='calendar'></div>
@endsection

@push('script')
<script>
    const token = '{{csrf_token()}}';
</script>
<script src="{{ asset('/js/nonOperatingDays.min.js') }}"></script>
@endpush