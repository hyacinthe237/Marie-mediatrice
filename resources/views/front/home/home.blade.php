@extends('front.templates.body')

@section('head')
    <title>Cameroonian Association of Australia | CAA Sydney</title>
@endsection


@section('body')
<section class="_section">
    <div class="home-header">
        <div class="_overlay">
            <div class="v-align-center">
                <div class="v-align-center-content">
                    <h3>Sanctuaire Marie Médiatrice d'Etoudi</h3>
                    <h4 class="mt-20">Yaoundé, Cameroon</h4>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="_section bg-white pt-40 pb-40">
    <div class="home-mission">
        <div class="container">
            <h2 class="bold green">Mission Statement</h2>

            <div class="text mt-20">
                <p>
                    Our mission as stated in our constitution:
                </p>

                <p>
                    To Promote good relations and understanding between Cameroonian People and the wider Australian community
                </p>

                <p>
                    To encourage the pursuit of education and employment amongst community members and especially the youth.
                </p>

                <p>
                    To promote cultural diversity within the Cameroonian community and the Australian community at large.
                </p>

                <p>
                    To provide assistance and advocacy to registered members and friends of the CAA community.
                </p>

                <p>
                    To empower members to understand the Australian Service delivery system to access services available confidently and independently.
                </p>

                <p>
                    Encourage collaboration and partnership with International Cameroonian organisations and other Educational institutions to promote Cameroonian culture.
                </p>
            </div>

            <div class="text-right mt-20">
                <a href="/assets/img/constitution.pdf" class="btn btn-lg btn-primary" target="_blank">
                    <i class="feather icon-download"></i>
                    Download Constitution
                </a>
            </div>
        </div>
    </div>
</section>

{{-- @include('front.home.latest') --}}


<section class="_section bg-white pt-40 pb-40">
    <div class="home-events">
        <div class="container">
            <h2 class="bold red">Calendar</h2>

            <div class="row mt-20">
                {{-- @foreach ($events as $item)
                    <div class="col-sm-6">
                        <div class="home-events-item">
                            <div class="date">
                                <div class="day">{{ date('d', strtotime($item->date)) }}</div>
                                <div class="month">{{ date('M', strtotime($item->date)) }}</div>
                            </div>

                            <div class="content">
                                <div class="title">{{ $item->title }}</div>
                                <div class="mini"> {{ $item->address }}</div>

                                <div class="mini">
                                    <i class="feather icon-clock"></i> {{ $item->time() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach --}}

            </div>

            {{--
            <h4 class="teal mt-40">Archives</h4>

           <div class="row mt-20">
                <div class="col-sm-4">
                    <a href="">
                        <div class="home-events-activity">
                            <img src="/assets/img/soccer.jpeg" class="img-responsive">
                            <h5 class="mt-20">African Cup NSW 2019</h5>
                        </div>
                    </a>
                </div>

                <div class="col-sm-4">
                    <a href="">
                        <div class="home-events-activity">
                            <img src="/assets/img/soccer.jpeg" class="img-responsive">
                            <h5 class="mt-20">Bankstown Children Festival 2019</h5>
                        </div>
                    </a>
                </div>

                <div class="col-sm-4">
                    <a href="">
                        <div class="home-events-activity">
                            <img src="/assets/img/soccer.jpeg" class="img-responsive">
                            <h5 class="mt-20">Easter 2019 Canberra Trip</h5>
                        </div>
                    </a>
                </div>
            </div>

            <div class="text-right mt-20">
                <a href="" class="btn btn-lg btn-danger w-200">
                    <i class="feather icon-calendar"></i>
                    All Activities
                </a>
            </div>
            --}}
        </div>
    </div>
</section>

{{-- @include('front.home.sponsors') --}}

@include('front.home.contact')

@endsection
