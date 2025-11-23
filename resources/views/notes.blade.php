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
    <div class="grid grid-cols-2 gap-2">
        <div>
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
        <!-- Floating Chatbot Button and Panel -->
        <button id="open-chatbot-btn" type="button" class="button is-info"
            style="position: fixed; bottom: 32px; right: 32px; z-index: 2001; border-radius:100px; box-shadow:0 4px 12px #8882; display:flex;align-items:center;gap:8px;">
            <span style="font-size:20px;">💬</span> Zalibot
        </button>
        <div id="chatbot-float-container" style="display:none;position:fixed;bottom:80px;right:32px;width:370px;max-width:94vw;z-index:2001;box-shadow:0 8px 32px #8882;">
            <div id="chatbot-container" style="display:flex;flex-direction:column;height:500px;max-height:70vh;border:1px solid #d1d5db;border-radius:12px;overflow:hidden;background:#fafafc;position:relative;">
                <div style="display:flex;align-items:center;border-bottom:1px solid #e5e7eb;padding:12px 16px;background:#f3f4f6;">
                    <span style="font-weight:600;letter-spacing:0.5px;">Zalibot</span>
                    <button id="close-chatbot-btn" type="button" class="button is-light is-small"
                        title="Tutup" style="margin-left:auto;font-size:18px;line-height:1;">✖️</button>
                </div>
                <div id="chatbot-messages" style="flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:12px;">
                    <!-- Chat messages will appear here -->
                </div>
                <form id="chatbot-form" style="display:flex;padding:12px 16px;border-top:1px solid #d1d5db;gap:8px;background:#fff;">
                    <input type="text" id="chatbot-input" name="message" class="input is-rounded" placeholder="Tulis pesan..." style="flex:1;" autocomplete="off" required>
                    <button type="submit" class="button is-info">Kirim</button>
                </form>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Floating open/close logic
                const openBtn = document.getElementById('open-chatbot-btn');
                const floatContainer = document.getElementById('chatbot-float-container');
                const closeBtn = document.getElementById('close-chatbot-btn');
                if (openBtn && floatContainer && closeBtn) {
                    openBtn.addEventListener('click', function() {
                        floatContainer.style.display = 'block';
                        setTimeout(() => {
                            // Animate in (for smoothness)
                            floatContainer.style.opacity = 1;
                        }, 1);
                    });
                    closeBtn.addEventListener('click', function() {
                        floatContainer.style.display = 'none';
                    });
                }

                // Chatbot logic as before
                const form = document.getElementById('chatbot-form');
                const input = document.getElementById('chatbot-input');
                const messages = document.getElementById('chatbot-messages');

                function appendMessage(text, who) {
                    const msgDiv = document.createElement('div');
                    msgDiv.style.maxWidth = '80%';
                    msgDiv.style.wordBreak = 'break-word';
                    msgDiv.style.padding = '10px';
                    msgDiv.style.borderRadius = '14px';
                    msgDiv.style.marginBottom = '2px';
                    msgDiv.style.alignSelf = who === 'user' ? 'flex-end' : 'flex-start';
                    msgDiv.style.background = who === 'user' ? '#4FC3F7' : '#eee';
                    msgDiv.style.color = who === 'user' ? '#fff' : '#222';
                    msgDiv.innerText = text;
                    messages.appendChild(msgDiv);
                    messages.scrollTop = messages.scrollHeight;
                }

                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const message = input.value.trim();
                        if (!message) return;
                        appendMessage(message, 'user');
                        input.value = '';
                        input.disabled = true;

                        fetch("{{ route('helo-gem') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    message: message
                                })
                            })
                            .then(resp => {
                                const contentType = resp.headers.get('Content-Type') || '';
                                if (contentType.includes('text/event-stream')) {
                                    if (!window.ReadableStream || !resp.body) return resp.text();
                                    const reader = resp.body.getReader();
                                    let buffer = '';

                                    function pump() {
                                        return reader.read().then(({
                                            done,
                                            value
                                        }) => {
                                            if (done) {
                                                if (buffer.trim()) appendMessage(buffer, 'bot');
                                                return;
                                            }
                                            const chunk = new TextDecoder("utf-8").decode(value);
                                            buffer += chunk;
                                            appendMessage(chunk, 'bot');
                                            return pump();
                                        });
                                    }
                                    return pump();
                                } else {
                                    return resp.text();
                                }
                            })
                            .then(data => {
                                if (typeof data === 'string' && data.trim().length > 0) {
                                    appendMessage(data, 'bot');
                                }
                            })
                            .catch((err) => {
                                console.log(err);
                                appendMessage("Terjadi kesalahan. Silakan coba lagi.", 'bot');
                            })
                            .finally(() => {
                                input.disabled = false;
                                input.focus();
                            });
                    });
                }
            });
        </script>
        <style>
            #chatbot-float-container {
                opacity: 1;
                transition: opacity .2s;
            }

            #chatbot-float-container[style*="display: none"] {
                opacity: 0;
            }

            #chatbot-container {
                min-width: 0;
                width: 100%;
                min-height: 350px;
            }

            @media (max-width: 700px) {
                #chatbot-float-container {
                    right: 4vw;
                    width: 97vw;
                    min-width: 0;
                }

                #chatbot-container {
                    height: 340px;
                    max-height: 50vh;
                    font-size: 0.93em;
                }

                #chatbot-messages {
                    padding: 10px;
                }
            }
        </style>
    </div>

</div>

@endsection