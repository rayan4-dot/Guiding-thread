<div class="overflow-x-auto">
    <table class="table table-zebra">
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- @slot('body') --}}
            {{ $slot }}
        </tbody>
    </table>
</div>