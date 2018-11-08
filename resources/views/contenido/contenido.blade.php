    @extends('principal')
    @section('contenido')

        @if(Auth::check())
            @if (Auth::user()->idrol == 1)
                <template v-if="menu==1">
                    <cliente></cliente>
                </template>
                <template v-if="menu==2">
                    <pedido></pedido>
                </template>
                <template v-if="menu==0">
                    <user></user>
                </template>
            @elseif (Auth::user()->idrol == 2)
                <template v-if="menu==1">
                    <cliente></cliente>
                </template>
            @else

            @endif

        @endif
    @endsection

