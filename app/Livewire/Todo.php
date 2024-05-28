<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Todo extends Component
{
    use WithPagination;

    public $name;
    public $search;

    public $editingTodoId;
    public $editingTodoName;
    protected $rules = [
        'name' => 'required|string',
    ];

    public function createTodo()
    {
        $this->validate();

        \App\Models\Todo::create([
            'name' => $this->name,
        ]);

        $this->reset('name');
        session()->flash('success', 'Todo created successfully');

        $this->resetPage();
    }

    public function deleteTodo($todoId)
    {
        $todo = \App\Models\Todo::find($todoId);

        if ($todo) {
            $todo->delete();
            session()->flash('success', 'Todo deleted successfully');
        }
    }
    public function edit($todoId)
    {
        $this->editingTodoId = $todoId;
        $this->editingTodoName = \App\Models\Todo::find($todoId)->name;
    }

    public function updateTodo()
    {
        $this->validate([
            'editingTodoName' => 'required|string',
        ]);

        if ($this->editingTodoId) {
            $todo = \App\Models\Todo::find($this->editingTodoId);
            $todo->name = $this->editingTodoName;
            $todo->save();

            session()->flash('success', 'Todo updated successfully');
        }

        $this->reset('editingTodoId', 'editingTodoName');

        $this->resetPage();
    }

    public function cancelEditing()
    {
        $this->reset('editingTodoId', 'editingTodoName');
    }
    public function render()
    {
        $query = \App\Models\Todo::latest();

        if (!empty($this->search)) {
            $query->where('name', 'like', "%{$this->search}%");
            $todos = $query->paginate(5);

            if ($todos->isEmpty()) {
                session()->flash('search-success', 'Todo not found');
            } else {
                session()->flash('search-success', 'Todo has been found');
            }
        } else {
            $todos = $query->paginate(5);
        }

        return view('livewire.todo', ['todos' => $todos]);
    }








}
