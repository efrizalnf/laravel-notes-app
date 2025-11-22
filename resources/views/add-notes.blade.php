@extends('layout.template')
@section('container')
<div class="container mt-2">
    <h1 class="has-text-centered h1 is-size-1">{{ request()->routeIs('edit-note') ? 'EDIT NOTES' : 'TAMBAH NOTES'}}</h1>
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
    <form method="POST" action="{{ request()->routeIs('add-note') ? route('save-note') : route('save-edit-note', isset($data) ? $data->id : '')}}" class="field" enctype="multipart/form-data">
        @csrf
        <div class="title">
            <label class="label" for="title">
                Title
            </label>
            <input class="input @error('title') is-danger @enderror" type="text" id="title" name="title" value="{{ request()->routeIs('edit-note') && isset($data) ? $data->title : old('title')}}">
            @error('title')
            <p class="help is-danger">Tidak boleh lebih dari 25 char</p>
            @enderror
        </div>
        <div class="content">
            <label class="label" for="">Content</label>
            <textarea class="input @error('content') is-danger @enderror" name="content" id="" cols="30" rows="10">{{ request()->routeIs('edit-note') && isset($data) ? $data->content : old('content', '') }}</textarea>
            @error('content')
            <p class="help is-danger">Tidak boleh kosong!</p>
            @enderror
        </div>
        <div class="form-group mb-5 mt-5">
            <label class="label">Image</label>
            <input type="file" name="image" class="form-control">
            @if(request()->routeIs('edit-note') && isset($data) && $data->image_path)
            <p class="help">Gambar saat ini: <img src="{{ asset('storage/images/' . $data->image_path) }}" alt="Current image" width="100" height="75"></p>
            @endif
        </div>
        @csrf
        @if (request()->routeIs('add-note'))
        @method('POST')
        @elseif (request()->routeIs('edit-note'))
        @method('PUT')
        @endif
        <a href="{{ route('note-lists') }}" class="button is-warning">Kembali</a>
        <input type="submit" value="SUBMIT" class="button is-success">
    </form>
</div>
@endsection