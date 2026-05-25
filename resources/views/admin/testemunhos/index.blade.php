@extends('layouts.admin')

@section('title', 'Testemunhos')

@section('content')
<div x-data="testemunhosApp()">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-[26px] font-bold text-gray-900">Testemunhos</h1>
            <p class="text-sm text-gray-500 mt-0.5">Gerir avaliações e comentários dos hóspedes.</p>
        </div>
        <button @click="addModal.open = true"
                class="inline-flex items-center gap-2 bg-gray-900 text-white text-sm font-medium px-4 py-2.5 rounded-full hover:bg-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Adicionar
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $counts['all'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Activos</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $counts['active'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Inactivos</p>
            <p class="text-3xl font-bold text-red-500 mt-1">{{ $counts['inactive'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Média</p>
            <div class="flex items-center gap-1.5 mt-1">
                <p class="text-3xl font-bold text-amber-500">{{ $counts['avg'] }}</p>
                <svg class="w-5 h-5 text-amber-400 mb-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Drag hint --}}
    @if($testimonials->count() > 1)
    <p class="text-xs text-gray-400 mb-3 flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
        Arraste os cartões para reordenar
    </p>
    @endif

    {{-- Cards Grid --}}
    <div id="sortable-list" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
        @forelse($testimonials as $t)
        <div class="testimonial-card bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col"
             data-id="{{ $t->id }}"
             :class="!{{ $t->active ? 'true' : 'false' }} ? 'opacity-60' : ''">

            {{-- Card top --}}
            <div class="px-5 pt-5 pb-4 flex-1">
                {{-- Drag handle + stars --}}
                <div class="flex items-center justify-between mb-3">
                    <div class="drag-handle cursor-grab active:cursor-grabbing text-gray-300 hover:text-gray-500 transition" title="Arrastar para reordenar">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 6a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm0 6a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm0 6a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm8-12a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm0 6a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm0 6a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                    </div>
                    <div class="flex gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $t->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>
                </div>

                {{-- Quote --}}
                <p class="text-sm text-gray-600 leading-relaxed line-clamp-4 mb-4">{{ $t->content_pt }}</p>

                {{-- Author --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                         style="background:#c99f5b">
                        {{ strtoupper(substr($t->author_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $t->author_name }}</p>
                        @if($t->author_location)
                        <p class="text-xs text-gray-400">{{ $t->author_location }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card footer --}}
            <div class="px-5 py-3 border-t border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    {{-- Toggle --}}
                    <form method="POST" action="{{ route('admin.testemunhos.toggle', $t) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $t->active ? 'bg-green-500' : 'bg-gray-200' }}"
                                title="{{ $t->active ? 'Desactivar' : 'Activar' }}">
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition {{ $t->active ? 'translate-x-[18px]' : 'translate-x-[2px]' }}"></span>
                        </button>
                    </form>
                    <span class="text-xs {{ $t->active ? 'text-green-600' : 'text-gray-400' }}">
                        {{ $t->active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div class="flex items-center gap-1">
                    <button @click="openEdit({{ $t->toJson() }})"
                            class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition" title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button @click="openDelete({{ $t->toJson() }})"
                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-14 text-center">
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-700">Sem testemunhos</p>
            <p class="text-xs text-gray-400 mt-1">Adicione o primeiro.</p>
        </div>
        @endforelse
    </div>


    {{-- ===== MODALS ===== --}}

    {{-- Add Modal --}}
    <div x-show="addModal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @click.self="addModal.open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Adicionar Testemunho</h3>
                <button @click="addModal.open = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.testemunhos.store') }}" class="px-6 py-5 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome do autor *</label>
                        <input type="text" name="author_name" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Localização</label>
                        <input type="text" name="author_location" placeholder="ex: Casal de Lisboa"
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                </div>

                {{-- Star rating picker --}}
                <div x-data="{ rating: 5 }">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Classificação *</label>
                    <input type="hidden" name="rating" :value="rating">
                    <div class="flex gap-1">
                        <template x-for="i in 5" :key="i">
                            <button type="button" @click="rating = i"
                                    class="transition hover:scale-110">
                                <svg class="w-7 h-7 transition" :class="i <= rating ? 'text-amber-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        </template>
                        <span class="ml-2 text-sm text-gray-500 self-center" x-text="rating + '/5'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Conteúdo PT *</label>
                        <textarea name="content_pt" rows="4" required
                                  class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Conteúdo EN *</label>
                        <textarea name="content_en" rows="4" required
                                  class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20 resize-none"></textarea>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" id="add_active" checked class="rounded">
                    <label for="add_active" class="text-sm text-gray-700">Activo</label>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" @click="addModal.open = false"
                            class="text-sm text-gray-600 px-4 py-2 rounded-full hover:bg-gray-100 transition">Cancelar</button>
                    <button type="submit"
                            class="text-sm bg-gray-900 text-white px-5 py-2 rounded-full hover:bg-gray-700 transition font-medium">Adicionar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div x-show="editModal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @click.self="editModal.open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Editar Testemunho</h3>
                <button @click="editModal.open = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" :action="editModal.action" class="px-6 py-5 space-y-4">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nome do autor *</label>
                        <input type="text" name="author_name" x-model="editModal.author_name" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Localização</label>
                        <input type="text" name="author_location" x-model="editModal.author_location"
                               class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
                    </div>
                </div>

                {{-- Star rating picker (edit) --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Classificação *</label>
                    <input type="hidden" name="rating" :value="editModal.rating">
                    <div class="flex gap-1">
                        <template x-for="i in 5" :key="i">
                            <button type="button" @click="editModal.rating = i"
                                    class="transition hover:scale-110">
                                <svg class="w-7 h-7 transition" :class="i <= editModal.rating ? 'text-amber-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        </template>
                        <span class="ml-2 text-sm text-gray-500 self-center" x-text="editModal.rating + '/5'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Conteúdo PT *</label>
                        <textarea name="content_pt" rows="4" x-model="editModal.content_pt" required
                                  class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Conteúdo EN *</label>
                        <textarea name="content_en" rows="4" x-model="editModal.content_en" required
                                  class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900/20 resize-none"></textarea>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" id="edit_active" x-model="editModal.active" class="rounded">
                    <label for="edit_active" class="text-sm text-gray-700">Activo</label>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" @click="editModal.open = false"
                            class="text-sm text-gray-600 px-4 py-2 rounded-full hover:bg-gray-100 transition">Cancelar</button>
                    <button type="submit"
                            class="text-sm bg-gray-900 text-white px-5 py-2 rounded-full hover:bg-gray-700 transition font-medium">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="deleteModal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
         @click.self="deleteModal.open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm" @click.stop>
            <div class="px-6 py-6 text-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">Eliminar Testemunho</h3>
                <p class="text-sm text-gray-500 mb-5">Eliminar o testemunho de <strong x-text="deleteModal.name"></strong>?</p>
                <div class="flex justify-center gap-3">
                    <button @click="deleteModal.open = false"
                            class="text-sm text-gray-600 px-4 py-2 rounded-full hover:bg-gray-100 transition">Cancelar</button>
                    <form method="POST" :action="deleteModal.action" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="text-sm bg-red-600 text-white px-5 py-2 rounded-full hover:bg-red-700 transition font-medium">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
function testemunhosApp() {
    return {
        addModal:    { open: false },
        editModal:   { open: false, action: '', author_name: '', author_location: '', content_pt: '', content_en: '', rating: 5, active: true },
        deleteModal: { open: false, action: '', name: '' },

        openEdit(t) {
            this.editModal.action          = `/admin/testemunhos/${t.id}`;
            this.editModal.author_name     = t.author_name;
            this.editModal.author_location = t.author_location ?? '';
            this.editModal.content_pt      = t.content_pt;
            this.editModal.content_en      = t.content_en;
            this.editModal.rating          = t.rating;
            this.editModal.active          = Boolean(t.active);
            this.editModal.open            = true;
        },

        openDelete(t) {
            this.deleteModal.action = `/admin/testemunhos/${t.id}`;
            this.deleteModal.name   = t.author_name;
            this.deleteModal.open   = true;
        },
    };
}

document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('sortable-list');
    if (!list) return;

    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'opacity-40',
        onEnd() {
            const ids = [...list.querySelectorAll('.testimonial-card')].map(el => el.dataset.id);
            fetch('{{ route('admin.testemunhos.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ order: ids }),
            });
        },
    });
});
</script>
@endpush
