@extends('auth::layouts.auth')

@section('title', trans('auth::auth.login'))

@section('styles')
    @parent
    <link href="{{ mix('/assets/auth/css/module.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="{{ mix('/assets/auth/js/login.js') }}"></script>
@endsection

@section('content')

<div id="login">
    <login :validation="{{$login_form_validator->renderRules()}}"></login>
</div>

@endsection