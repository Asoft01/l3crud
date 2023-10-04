<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;
    #[Rule('required|min:3|max:50')]
    
    public $name; 
    public $search;
    public function create(){
        // dd('test');
        // validate 
        // create the todo 
        // clear the input 
        // send flash message 

        // $this->validate();
        $validated = $this->validateOnly('name'); 

        Todo::create($validated);

        $this->reset('name');

        session()->flash('success', 'created');
    }
    public function render()
    {
        return view('livewire.todo-list', [
            // 'todos' => Todo::latest()->get()
            'todos' => Todo::latest()->paginate(5)
        ]);
    }
}
