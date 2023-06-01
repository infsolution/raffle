<x-app-layout>
<h1>{{$raffle->title}}</h1>
    @if(auth()->user())
    <form action="">
        <input type="file" name="image" id="">
    </form>
    @endif

</x-app-layout>