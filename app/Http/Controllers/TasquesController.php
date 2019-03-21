<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserTasksIndex;
use App\Tag;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasquesController extends Controller
{

    public function index(UserTasksIndex $request)
    {
        if (Auth::user()->can('tasks.manage')) {
            $tasks =  map_collection(Task::orderBy('created_at','desc')->get());
            $uri = '/api/v1/tasks';
        } else {
//            dd('hola');
//        dd('hola');
            $tasks =  map_collection($request->user()->tasks);
            $uri = '/api/v1/user/tasks';
        }
        $users = map_collection(User::all());
        $tags = map_collection(Tag::all());
        return view('tasques',compact('tasks','users','uri','tags'));
    }

//    public function addTagTask(Request $request){
//
//
//
//    }
}
