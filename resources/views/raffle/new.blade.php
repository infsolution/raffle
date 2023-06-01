<x-app-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('raffle.store') }}">
        @csrf


        <div class="mb-3">
            <x-input-label for="title" :value="__('Titulo')" />
            <x-text-input id="title" class="form-control" type="text" name="title" :value="old('title')" required autofocus autocomplete="title" />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

 
        <div class="mb-3">
            <x-input-label for="dscricao" :value="__('Descricao')" />

            <x-text-input id="title" class="form-control"
                            type="text"
                            name="description"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="drawn_date" :value="__('Data Sorteio')" />
            <x-text-input id="drawn_date" class="form-control" type="date" name="drawn_date" :value="old('drawn_date')"  autofocus />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="drawn_date" :value="__('Valor ponto')" />
            <x-text-input id="drawn_date" class="form-control" type="number" name="value_point" :value="old('value_point')"  autofocus  />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="number_point" :value="__('Numero de pontos')" />
            <x-text-input id="number_point" class="form-control" type="number" name="number_point" :value="old('number_point')"  autofocus/>
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="number_point" :value="__('Tipo de rifa')" />
            <x-select id="number_point" class="form-control" name="format" :value="old('format')"  autofocus autocomplete="format" />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="btn btn-primary">
                Criar rifa
            </x-primary-button>
        </div>
    </form>
</x-app-layout>