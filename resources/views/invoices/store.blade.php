<?php
public function store(Request $request)
{
    //validation rules
    $rules = [
        'title' => 'required|string|unique:todos,title|min:2|max:191',
        'note'  => 'required|string|min:5|max:1000',
    ];
    //custom validation error messages
    $messages = [
        'title.unique' => 'Invoice title should be unique', //syntax: field_name.rule
    ];
    //First Validate the form data
    $request->validate($rules,$messages);
    //Create a Todo
    $todo = new Todo;
    $todo->title = $request->title;
    $todo->body = $request->body;
    $todo->save(); // save it to the database.
    //Redirect to a specified route with flash message.
    return redirect()
        ->route('todos.index')
        ->with('status','Created a new Todo!');
}