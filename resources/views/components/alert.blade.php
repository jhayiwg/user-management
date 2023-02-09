@if ($errors->any())
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
    <p class="font-bold">Whoops! Something went wrong!</p>
    @foreach ($errors->all() as $message)
    <p>{{ $message }}</p>
    @endforeach
</div>
@endif