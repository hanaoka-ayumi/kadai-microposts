<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Micropost;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        
        return view('users.index', [
            'users' => $users,
        ]);
    }
    
    public function show($id)
    {
        $user = User::find($id);
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);

        $data = [
            'user' => $user,
            'microposts' => $microposts,
        ];

        $data += $this->counts($user);

        return view('users.show', $data);
    }
    
    public function followings($id)
    {
        $user = User::find($id);
        $followings = $user->followings()->paginate(10);

        $data = [
            'user' => $user,
            'users' => $followings,
        ];

        $data += $this->counts($user);

        return view('users.followings', $data);
    }

    public function followers($id)
    {
        $user = User::find($id);
        $followers = $user->followers()->paginate(10);

        $data = [
            'user' => $user,
            'users' => $followers,
        ];

        $data += $this->counts($user);

        return view('users.followers', $data);
    }
    
   public function feed_microposts()
    {
        $follow_user_ids = $this->followings()-> pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
        
         $favorite_user_ids = $this->favoriting()-> pluck('users.id')->toArray();
        $favorite_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $favorite_user_ids);
        
    }
    
     public function favoriting($id)
    {
        $user = User::find($id);
        $favoriting = $user->favoriting()->paginate(10);

        $data = [
            'user' => $user,
            'microposts' => $favoriting,
        ];

        $data += $this->counts($user);

        return view('users.favoriting', $data);
    }

    

}