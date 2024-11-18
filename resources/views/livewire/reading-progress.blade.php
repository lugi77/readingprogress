<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Book Tracker') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Flash Message -->
                @if (session()->has('message'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Add/Edit Form -->
                <form wire:submit.prevent="{{ $bookId ? 'update' : 'store' }}"
                    class="space-y-6 mb-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                        {{ $bookId ? 'Edit Reading Progress' : 'Add to Reading Progress' }}
                    </h2>

                    <!-- Title and Author Inputs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col">
                            <label for="title"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-200">Title</label>
                            <input type="text" id="title" wire:model="title"
                                class="mt-2 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-700"
                                placeholder="Enter book title">
                            @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex flex-col">
                            <label for="author"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-200">Author</label>
                            <input type="text" id="author" wire:model="author"
                                class="mt-2 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-700"
                                placeholder="Enter author's name">
                            @error('author') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Genre Input -->
                    <div class="flex flex-col">
                        <label for="genre" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Genre</label>
                        <input type="text" id="genre" wire:model="genre"
                            class="mt-2 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-700"
                            placeholder="Enter book genre">
                        @error('genre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Progress Input -->
                    <div class="flex flex-col">
                        <label for="progress" class="text-sm font-semibold text-gray-700 dark:text-gray-200">Progress
                            (%)</label>
                        <input type="number" id="progress" wire:model="progress"
                            class="mt-2 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-700"
                            min="0" max="100" placeholder="Enter reading progress" required>
                        @error('progress') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full bg-blue-600 dark:bg-blue-700 text-white py-3 rounded-lg font-medium hover:bg-blue-700 dark:hover:bg-blue-800 transition duration-300">
                            {{ $bookId ? 'Update Progress' : 'Add to Progress' }}
                        </button>
                    </div>
                </form>


                <!-- Confirmation Modal -->
                <div x-data="{ open: @entangle('openDeleteModal') }" x-show="open" @keydown.escape.window="open = false"
                    style="display: none;"
                    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Are you sure you want to
                            delete this book?</h3>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">This action cannot be undone.</p>
                        <div class="mt-6 flex justify-end space-x-4">
                            <button @click="open = false"
                                class="bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg">Cancel</button>
                            <button wire:click="delete"
                                class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-300">Delete</button>
                        </div>
                    </div>
                </div>


                <!-- Reading Progress Table -->
                <div class="overflow-x-auto">
                    <table class="text-white w-full max-w-6xl border-collapse mt-6 text-center">
                        <thead>
                            <tr class="bg-gray-700 dark:bg-gray-900">
                                <th class="px-6 py-3 border-b font-semibold text-lg text-gray-100 dark:text-gray-200">
                                    Title</th>
                                <th class="px-6 py-3 border-b font-semibold text-lg text-gray-100 dark:text-gray-200">
                                    Author</th>
                                <th class="px-6 py-3 border-b font-semibold text-lg text-gray-100 dark:text-gray-200">
                                    Progress</th>
                                <th class="px-6 py-3 border-b font-semibold text-lg text-gray-100 dark:text-gray-200">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                                <tr class="bg-gray-50 dark:bg-gray-800">
                                    <td class="px-6 py-4 border-b text-gray-900 dark:text-gray-300">{{ $book->title }}</td>
                                    <td class="px-6 py-4 border-b text-gray-900 dark:text-gray-300">{{ $book->author }}</td>
                                    <td class="px-6 py-4 border-b text-gray-900 dark:text-gray-300">{{ $book->progress }}%
                                    </td>
                                    <td class="px-6 py-4 border-b">
                                        <button wire:click="edit({{ $book->id }})"
                                            class="text-blue-500 hover:text-blue-700">Edit</button>
                                        <button wire:click="confirmDelete({{ $book->id }})"
                                            class="text-red-500 hover:text-red-700 ml-4">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>