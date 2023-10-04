<?php

namespace App\Livewire;

use App\Models\Todo;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;
    #[Rule('required|min:3|max:50')]
    
    public $name; 
    public $search;
    public $editingTodoID; 
    #[Rule('required|min:3|max:50')]
    public $editingTodoName;

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

        $this->resetPage();
    }

    public function delete($todoID){
        // Todo::find($todoID)->delete();
        try{
            Todo::findOrFail($todoID)->delete();
        }catch(Exception $e){
            session()->flash('error', 'Failed to delete todo!');
            return;
        }
    }

    // public function delete(Todo $todo){
    //     $todo->delete();
    // }

    public function toggle($todoID){
        $todo = Todo::find($todoID); 
        $todo->completed = !$todo->completed; 
        $todo->save();
    }

    public function edit($todoID){
        $this->editingTodoID = $todoID; 
        $this->editingTodoName = Todo::find($todoID)->name;

    }

    public function cancelEdit(){
        $this->reset('editingTodoID', 'editingTodoName');
    }

    public function update(){
        $this->validateOnly('editingTodoName');

        Todo::find($this->editingTodoID)->update(
            [
                'name' => $this->editingTodoName
            ]
        );

        $this->cancelEdit();
    }

    public function render()
    {
        // return view('livewire.todo-list', [
        //     // 'todos' => Todo::latest()->get()
        //     'todos' => Todo::latest()->paginate(5)
        // ]);

        return view('livewire.todo-list', [
            // 'todos' => Todo::latest()->get()
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5)
        ]);
    }
}
