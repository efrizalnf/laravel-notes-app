@extends('layout.template')
@section('container')

<div class="container">
    <h1 class="has-text-centered h1 is-size-1">TODO LIST ME</h1>
    <a href="{{ route('recycle_bin') }}" class="button is-info m-3">Tong sampah</a>
    <a href="{{ route('add-note') }}" rel="noopener noreferrer" class="button is-info m-3">Tambah</a>
    @if(session()->has('success'))
    <div class="notification is-success">
        {{ session()->get('success') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="notification is-danger">
        {{ session()->get('error') }}
    </div>
    @endif
    <table cellpadding="10" border="1" class="table is-bordered">
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Content</th>
            <th>Image</th>
            <th>Edit</th>
        </tr>
        @if ($notes->isEmpty())
        <td colspan="5" class="has-text-centered w-100 m-auto">No data!</td>
        @else
        @foreach ($notes as $note )
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $note->title }}</td>
            <td>{{ $note->content }}</td>
            <td> @if($note->image_path)
                <img src="{{ asset('storage/images/' . $note->image_path) }}" alt="Image" width="150" height="100">
                @endif
            </td>
            <td>
                <button class="button is-warning m-3"><a href="{{ route('edit-note', ['id' => $note->id]) }}" rel="noopener noreferrer">Edit</a></button>
                <form method="POST" action="{{ route('delete-note', $note->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button is-danger" onclick="return confirm('Apakah anda yakin mau hapus data?')">Delete</button>  
                </form>
            </td>
        </tr>
        @endforeach
        @endif
    </table>

</div>

@endsection