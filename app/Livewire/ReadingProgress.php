<?php

namespace App\Livewire;

use App\Models\Progress;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]

class ReadingProgress extends Component
{
    use WithPagination;

    public $title, $author, $progress, $bookId, $genre; // Store form data
    public $search = '';
    public $perPage = 5;

    // For showing and handling editing
    public $openEditModal = false;
    public $openDeleteModal = false;

    // Store new progress (Add a book to progress)
    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'progress' => 'required|numeric|min:0|max:100',
            'genre' => 'nullable|string|max:255',
        ]);

        // Create a new book in progress
        Progress::create([
            'title' => $this->title,
            'author' => $this->author,
            'status' => 'Reading',
            'progress' => $this->progress,
            'genre' => $this->genre, 
        ]);

        session()->flash('message', 'Book added to your reading progress!');
        $this->resetForm();
    }

    // Edit book progress
    public function edit($id)
    {
        $book = Progress::findOrFail($id);

        // Prepopulate the form fields for editing
        $this->bookId = $book->id;
        $this->title = $book->title;
        $this->author = $book->author;
        $this->progress = $book->progress;
        $this->genre = $book->genre;

        $this->openEditModal = true; // Open modal for editing
    }

    // Update the book progress
    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'progress' => 'required|numeric|min:0|max:100',
            'genre' => 'nullable|string|max:255',
        ]);

        $book = Progress::findOrFail($this->bookId);
        $book->update([
            'title' => $this->title,
            'author' => $this->author,
            'progress' => $this->progress,
            'genre' => $this->genre, 
        ]);

        session()->flash('message', 'Reading progress updated!');
        $this->resetForm();
        $this->openEditModal = false;
    }

     // Show the delete confirmation modal
     public function confirmDelete($id)
     {
         $this->bookId = $id;
         $this->openDeleteModal = true;
     }

    // Delete a book from the reading progress
     public function delete()
     {
         $book = Progress::findOrFail($this->bookId);
         $book->delete();
 
         session()->flash('message', 'Book removed from reading progress!');
         $this->openDeleteModal = false;
     }

    // Reset form fields
    private function resetForm()
    {
        $this->title = '';
        $this->author = '';
        $this->progress = '';
        $this->bookId = null;
        $this->genre = '';
    }

    public function render()
    {
        $books = Progress::where('status', 'Reading')
            ->where(function($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('author', 'like', '%' . $this->search . '%');
            })
            ->paginate($this->perPage); // Use pagination here

        return view('livewire.reading-progress', [
            'books' => $books, // Pass paginated books
        ]);
    }
}
