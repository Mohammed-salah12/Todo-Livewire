<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Todo extends Component
{
    public $name;
    public $todos;

    public function mount()
    {
        $this->todos = \App\Models\Todo::all();
    }

    public function createTodo()
    {
        $this->validate([
            'name' => 'required|string',
        ]);

        \App\Models\Todo::create([
            'name' => $this->name
        ]);

        $this->reset(['name']);
        session()->flash('success', 'Todo created successfully');
        $this->todos = \App\Models\Todo::all(); // Refresh the todos list
    }
    public function deleteTodo($todoId)
    {
        $todo = \App\Models\Todo::find($todoId);

        if ($todo) {
            $todo->delete();
            session()->flash('success', 'Todo deleted successfully');
            // Update $todos property after successful deletion
            $this->todos = \App\Models\Todo::all(); // Refresh the todos list
        }
    }

    public function render()
    {
        return view('livewire.todo', ['todos' => $this->todos]);
    }
}
