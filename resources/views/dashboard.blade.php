<x-app-layout>
    <h1>Todas suas rifas</h1>  
<a href="{{ route('raffle.create') }}" class="btn btn-primary">Nova rifa</a>
  <div class="container">
    <div class="row">
        <div class="col">
            Nome
        </div>
        <div class="col">
            Descriçao
        </div>
        <div class="col">
            Sorteio
        </div>
        <div class="col">
            Valor do ponto.
        </div>
        <div class="col">
            Açoes
        </div>
    </div>
    @if($raffles)
    @foreach($raffles as $raffle)
    <div class="row">
    <div class="col">
           <a href="{{ route('raffle.show', ['raffle'=>$raffle->id]) }}">{{$raffle->title}}</a> 
        </div>
        <div class="col">
        {{$raffle->description}}
        </div>
        <div class="col">
        {{$raffle->drawn_date}}
        </div>
        <div class="col">
        {{$raffle->value_point/100}}
        </div>
        <div class="col">
            <div class="row">
                <div class="col"><a href="{{route('raffle.edit',['raffle'=>$raffle->id])}}" class="btn btn-success">Editar</a></div>
                <div class="col"><a href="{{route('raffle.destroy',['raffle'=>$raffle->id])}}" class="btn btn-danger">Excluir</a></div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    voce ainda nao tem rifas
    @endif
  </div>

</x-app-layout>
