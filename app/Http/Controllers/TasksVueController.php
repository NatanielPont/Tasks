<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TasksVueController extends Controller
{
    //
    public function index()
    {

        $tasks=Task::all();
//        $tasks=[];
        return view('tasks_vue',compact('tasks'));
        
    }
}
