@extends('layout.template')
@section('container')

<div class="container">
    <div class="btns mt-3 mb-4">
        <a href="{{ route('note-lists') }}" class="m-3 button is-warning">Kembali</a>
        <a href="{{ route('delete-all') }}" class="m-3 button is-danger">Delete Semua data</a>
        <a href="{{ route('restore-all') }}" class="m-3 button is-success">Restore Semua data</a>
    </div>
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

        @if($data->isEmpty())
        <tr>
            <td colspan="5" class="has-text-centered w-100 m-auto">No Data!</td>
        </tr>
        @else
        @foreach ($data as $note )
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $note->title }}</td>
            <td>{{ $note->content }}</td>
            <td>
                @if($note->image_path)
                <img src="{{ asset('storage/images/' . $note->image_path) }}" alt="Image" width="150" height="100">
                @endif
            </td>
            <td>
                <form method="POST" action="{{ route('delete-forever', $note->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button is-danger m-3" onclick="return confirm('Apakah anda yakin mau hapus data?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        @endif

    </table>
</div>
@endsection