<nav class="navbar navbar-default"  id="navbar">
    <div class="container">
        <div class="navbar-header">
            {{-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="feather icon-menu"></span>
            </button> --}}
            <a class="navbar-brand" href="/">
                <img src="{{ asset('/assets/img/logos/caa-primary.png') }}" alt="CAA Sydney logo">
            </a>
        </div>
        {{-- <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="{{ route('home') }}">
                        News
                    </a>
                </li>
                <li>
                    <a href="">
                        Activities
                    </a>
                </li>
                <li>
                    <a href="">
                        Membership
                    </a>
                </li>
                <li>
                    <a href="">
                        Constitution
                    </a>
                </li>
                <li>
                    <a href="">
                        Contact
                    </a>
                </li>
            </ul>
        </div> --}}
    </div>
</nav>

@if ($fixed) 
<div class="navbar-clearfix"></div>
@endif
