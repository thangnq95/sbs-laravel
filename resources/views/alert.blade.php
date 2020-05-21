<!-- /resources/views/alert.blade.php -->

{{--<div class="alert alert-danger">--}}
{{--    {{ $slot }}--}}
{{--</div>--}}

<div class="alert alert-danger">
    <div class="alert-title">{{ $title }}</div>

    {{ $slot }}

    <p>Passing Additional Data To Components : {{ $foo }}</p>
</div>
