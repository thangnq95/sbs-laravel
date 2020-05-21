<!-- Stored in resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <p>This is my body content.</p>
{{--    @component('alert')--}}
{{--        <strong>Whoops!</strong> Something went wrong!--}}
{{--    @endcomponent--}}
    @component('alert', ['foo' => 'bar'])
        @slot('title')
            Forbidden
        @endslot

        You are not allowed to access this resource!
    @endcomponent
    @alert
        You are not allowed to access this resource!
    @endalert

@endsection
<script>
    var app = @json(['a'=>'aa']);
    console.log(app);
</script>
