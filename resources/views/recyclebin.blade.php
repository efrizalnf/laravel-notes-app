<div class="container">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    {{-- <form action="post" action="{{ route('delete-all') }}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete Semua Data</button>
    </form> --}}
    <a href="{{ route('delete-all') }}" class="m-3">Delete Semua data</a>
    <a href="{{ route('restore-all') }}" class="m-3">Restore Semua data</a>
    <table cellpadding="10" border="1">
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Content</th>
            <th>Edit</th>
        </tr>
        @foreach ($data as $note )
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $note->title }}</td>
            <td>{{ $note->content }}</td>
            <td>
                <form method="POST" action="{{ route('delete-forever', $note->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button is-danger m-3" onclick="confirm('Apakah anda yakin mau hapus data?')">Delete</button> 
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>