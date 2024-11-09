<?php

namespace App\Http\Controllers;

use App\Models\Notes as ModelsNotes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Notes extends Controller
{
    public function __construct() {}
    // main app
    public function index()
    {
        $notes = ModelsNotes::all();
        $user = User::all();
        // @dd($user[0]['name']);
        session()->put('user', $user);

        return view('notes', compact('notes'));
    }

    public function addnotes()
    {

        return view('add-notes');
    }

    public function save_note(Request $request)
    {
        $validation = $request->validate([
            'title' => 'required|max:25',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp|max:2048'
        ]);

        $imageFileName = null;
        if ($request->hasFile('image')) {
            $imageFileName = time() . '.' . $request->image->extension();
            $path = $request->file('image')->storeAs('images', $imageFileName, 'public');
            $request->image->storeAs($path, $imageFileName);
        }

        ModelsNotes::create([
            'title' => $validation['title'],
            'content' => $validation['content'],
            'image_path' => $imageFileName
        ]);

        return redirect()->route('note-lists')->with('success', 'Berhasil disimpan!');
    }

    public function editnotes($id)
    {
        $data =  ModelsNotes::find($id);
        $sesi = session()->get('user');
        // @dd($sesi[0]['name']);
        return view('add-notes', compact('data'));
    }

    public function save_edit_notes(Request $request, $id)
    {
        $validasi = $request->validate([
            'title' => 'required|max:25',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp|max:2048'
        ]);
        
        $data = ModelsNotes::find($id);
        if ($data) {
            if ($request->hasFile('image')) {
                Storage::disk('public')->delete('images/' . $data->image_path);
                $imageFileName = time() . '.' . $request->image->extension();
                $path = $request->file('image')->storeAs('images', $imageFileName, 'public');
                $request->image->storeAs($path, $imageFileName);
                $data->update([
                    'title' => $validasi['title'],
                    'content' => $validasi['content'],
                    'image_path' => $imageFileName
                ]);
            }else {
                $data->update([
                    'title' => $validasi['title'],
                    'content' => $validasi['content'],
                    'image_path' => $data->image_path
                ]);
            }
            
            return redirect()->route('note-lists')->with('success', 'Berhasil diedit!');
        } else {
            return redirect()->route('note-lists')->with('error', 'Catatan tidak ditemukan!');
        }
    }
    public function delete_note($id)
    {
        $note = ModelsNotes::find($id);
        if ($note) {
            
            $note->delete();
            return redirect()->route('note-lists')->with('success', 'Catatan berhasil dihapus!');
        } else {
            return redirect()->route('note-lists')->with('error', 'Catatan tidak ditemukan!');
        }
    }

    public function recyclebin()
    {
        $data = ModelsNotes::onlyTrashed()->get();
        return view('recyclebin', compact('data'));
    }

    public function delete_forever($id)
    {
        $note = ModelsNotes::onlyTrashed()->find($id);
        if ($note) {
            if ($note->image_path) {
                Storage::disk('public')->delete('images/' . $note->image_path);
            }
            $note->forceDelete();
            return back()->with('success', 'Catatan berhasil dihapus!');
        } else {
            return back()->with('error', 'Catatan tidak ditemukan!');
        }
    }

    public function delete_all()
    {
        $notes = ModelsNotes::onlyTrashed()->get();
        
        if ($notes->count() > 0) {
            foreach ($notes as $note) {
                if ($note->image_path) {
                    Storage::disk('public')->delete('images/' . $note->image_path);
                }
            }
            ModelsNotes::onlyTrashed()->forceDelete();
            return redirect()->route('note-lists')->with('success', 'Catatan berhasil dihapus!');
        } else {
            return redirect()->route('note-lists')->with('error', 'Catatan tidak ditemukan!');
        }
    }

    public function restore_all()
    {
        $note = ModelsNotes::onlyTrashed();
        $note->restore();

        return redirect()->route('note-lists');
    }
}
