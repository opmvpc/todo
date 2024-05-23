<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes tâches') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Nom')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')"
                        autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div class="flex items-center gap-4 mt-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                </div>
            </form>


            <div class="bg-white p-4 rounded shadow">
                <ul class="space-y-4">
                    @forelse ($tasks as $task)
                        <li class="flex justify-between">
                            <div class="flex space-x-2">
                                <form action="{{ route('tasks.update', $task) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="checkbox" name="is_done" id="is_done_{{ $task->id }}"
                                        @checked($task->is_done)>
                                    <x-input-label for="is_done_{{ $task->id }}" :value="$task->name" />
                                </form>
                            </div>
                            <button x-data="{ id: {{ $task->id }} }"
                                x-on:click.prevent="window.selected = id; $dispatch('open-modal', 'confirm-task-deletion');"
                                type="submit" class="text-red-400">Supprimer</button>
                        </li>
                    @empty
                        Vous n'avez pas encore de tâches.
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <script>
        const checkboxes = document.querySelectorAll("input[type='checkbox']");
        console.log(checkboxes);
        for (let index = 0; index < checkboxes.length; index++) {
            const checkbox = checkboxes[index];
            checkbox.addEventListener("change", (e) => {
                const form = e.currentTarget.closest("form");
                form.submit()
            })

        }
    </script>

    <x-modal name="confirm-task-deletion" focusable>
        <form method="post" onsubmit="event.target.action= '/tasks/' + window.selected" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Êtes-vous sûr de vouloir supprimer cette tâche ?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Cette action est irréversible. Toutes les données seront supprimées.
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>

                <x-danger-button class="ml-3" type="submit">
                    Supprimer
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
