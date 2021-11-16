@extends('front.templates.mobile')

@section('head')
    <title>CAA Sydney Chapter</title>
    <script>
        var _roles = <?php echo json_encode($roles);?>;
    </script>
@endsection


@section('body')
    @if (!Auth::check())
        <auth-login></auth-login>
    @else
        <core></core>
    @endif
@endsection
