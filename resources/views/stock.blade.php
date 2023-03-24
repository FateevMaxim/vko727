@section( 'chinaaddress', $config->address )
<x-app-layout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                @if(session()->has('message'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        <span class="font-medium">{{ session()->get('message') }}
                    </div>
                @endif
                    <div class="grid md:grid-cols-3 grid-cols-1 gap-3 h-22 pl-6 pr-6 pb-4">
                        <div class="grid grid round_border min_height grid-cols-1 p-4 relative">
                            <div>
                                 <span>
                                Пункт приёма
                                </span>
                                <h3>China</h3>
                            </div>
                            <div class="absolute p-4 bottom-0">
                                <span>Количество зарегистрированных трек кодов за сегодня</span>
                                <h3>{{ $count }}</h3>
                            </div>

                        </div>
                        <div id="track_codes_list" class="round_border min_height p-4">

                        </div>
                        <div class="grid hidden" id="clear_track_codes">

                        </div>
                        <div class="grid grid-cols-1 p-4 min_height round_border relative">
                            <div class="grid mx-auto">
                                <img src="{{ asset('images/barcode.jpg') }}" width="200" alt="Barcode">
                                <b class="mx-auto" style="margin-top: -45px;">Upload Data</b>
                            </div>
                            <div id="track">
                                <span>Счётчик</span>

                                <div x-data="{ count: 0 }">
                                    <h1 id="count"></h1>
                                </div>
                            </div>
                            <div class="absolute w-full bottom-0 p-4">
                                <form method="POST" action="{{ route('china-product') }}" id="searchForm">
                                    <div>
                                        <div>
                                            @csrf

                                            <x-primary-button class="mx-auto w-full">
                                                {{ __('Загрузить') }}
                                            </x-primary-button>
                                            <x-secondary-button class="mx-auto mt-4 w-full" id="clear">
                                                {{ __('Очистить') }}
                                            </x-secondary-button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>

                        <script>

                            let code = "";
                            let reading = false;
                            var number = 1;
                            document.addEventListener('keypress', e => {
                                //usually scanners throw an 'Enter' key at the end of read
                                if (e.keyCode === 13) {
                                    if(code.length > 5) {
                                        $('#track_codes_list').append('<h2>'+number+'. '+code+'</h2>');
                                        $('#clear_track_codes').append(code+'\r\n');
                                        $("#count").text(number);
                                        number++;
                                        code = "";
                                    }
                                } else {
                                    code += e.key; //while this is not an 'enter' it stores the every key
                                }

                                //run a timeout of 200ms at the first read and clear everything
                                if(!reading) {
                                    reading = true;
                                    setTimeout(() => {
                                        code = "";
                                        reading = false;
                                    }, 200);  //200 works fine for me but you can adjust it
                                }
                            });

                            /* прикрепить событие submit к форме */
                            $("#searchForm").submit(function(event) {
                                /* отключение стандартной отправки формы */
                                event.preventDefault();

                                /* собираем данные с элементов страницы: */
                                var $form = $( this ),
                                    track_codes = $("#clear_track_codes").html();
                                url = $form.attr( 'action' );

                                /* отправляем данные методом POST */
                                $.post( url, { track_codes: track_codes } )
                                    .done(function( data ) {
                                        location.reload();
                                    });

                            });

                            /* прикрепить событие submit к форме */
                            $("#clear").click(function(event) {
                                /* отключение стандартной отправки формы */
                                event.preventDefault();

                                $("#track_codes_list").html('');
                                $("#clear_track_codes").html('');
                                number = 1;
                                $("#count").text('0');

                            });

                        </script>
                    </div>
                    @include('components.scanner-settings')
            </div>
        </div>
</x-app-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/datepicker.min.js"></script>
