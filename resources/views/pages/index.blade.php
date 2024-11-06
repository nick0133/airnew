@extends('layouts.front')
@section('title')
    Компрессоры, СВП, запчасти в Москве - Продажа, ремонт и обслуживание компрессорной техники от компании Пневмосистема
@endsection
@section('content')
    <section id="sliders">
        <div class="container">
            <div class="row">
                <div class="swiper second-swiper">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        @foreach ($slider as $slide)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $slide) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>

                </div>
            </div>
            <div class="row">
                <div class="pags text-center"></div>
            </div>
        </div>
    </section>

    <section id="main-text">
        <div class="container">
            <div class="main_text">
                <div class="row">
                    <h1 class="main_h1 ">
                        Сертификат Системы менеджмента качества
                        Удостоверение №01-13-9101-01 Федеральной службы
                        по экологическому, техническому и атомному надзору.
                    </h1>
                    <p class="main_text_p">
                        Мы находимся в постоянном поиске оптимальных решений для наших клиентов. Квалифицированная
                        диагностика позволяет ремонтировать сломавшуюся деталь, а не менять блок целиком. Мы ищем
                        запчасти и расходные материалы не уступающие оригинальным запчастям производителя оборудования,
                        но выигрывающие по цене. Постоянно следим за новинками рынка компрессорного оборудования и
                        предлагаем нашим покупателям оптимальные по цене/качеству образцы.
                        Главным критерием в современном быстро меняющемся мире для нас является практика. Наработанный
                        опыт позволяет нашим специалистам принимать верные решения в сложны ситуациях как в случае
                        ремонта так и при подборе оборудования.
                    </p>
                    <p class="main_text_p">Вы можете позвонить нашим специалистам с самым сложным вопросом, мы
                        постараемся помочь.</p>
                    <p class="main_text_p">
                        Компания “Пневмосистема” имеет сертификат Системы менежмента качества ГОСТ ISO 9001-2011 (ISO
                        9001:2008) и Удостоверение №01-13-9101-01 Федеральной службы по экологическому, техническому и
                        атомному надзору.
                    </p>
                </div>
                <div class="row row_top">
                    <div class="col-md-6 col-6 main_text_bottom">
                        <h1 class="main_h1 index_h1">
                            Сертификат Системы менеджмента качества
                        </h1>
                        <p class="main_text_p">
                            Мы находимся в постоянном поиске оптимальных решений для наших клиентов. Квалифицированная
                            диагностика позволяет ремонтировать сломавшуюся деталь, а не менять блок целиком. Мы ищем
                            запчасти и расходные материалы не уступающие оригинальным запчастям производителя
                            оборудования, но выигрывающие по цене. Постоянно следим за новинками рынка компрессорного
                            оборудования и предлагаем нашим покупателям оптимальные по цене/качеству образцы.

                        </p>
                    </div>
                    <div class="col-md-6 col-6 main_text_bottom">
                        <h1 class="main_h1 index_h1">
                            Удостоверение Федеральной службы
                        </h1>
                        <p class="main_text_p">
                            Главным критерием в современном быстро меняющемся мире для нас является практика.
                            Наработанный опыт позволяет нашим специалистам принимать верные решения в сложны ситуациях
                            как в случае ремонта так и при подборе оборудования.

                        </p>
                        <div class="main_text_p">
                            Вы можете позвонить нашим специалистам с самым сложным вопросом, мы постараемся помочь.

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection