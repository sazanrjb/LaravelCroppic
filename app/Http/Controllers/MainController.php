<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class MainController extends Controller {


	public function index(){
		return view('index');
	}

	public function create(){
		return view('create');
	}


	public function edit($id){
		$user = User::findOrFail($id);

		return view('edit')->with('user',$user);
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function store()
	{
		$user =  new User();

		$filename_array = explode('/', Input::get('imgName'));
		$filename = $filename_array[sizeof($filename_array)-1];

		$user->name = Input::get('name');
		$user->picture = '/uploads/'.$filename;
		if($user->save()){

			rename('temp/'.$filename,'uploads/'.$filename);
			Session::flash('notice','Success');
			return redirect('/');
		}
	}

	public function update($id){
		$user = User::findOrFail($id);

		$filename_array = explode('/', Input::get('imgName'));
		$filename = $filename_array[sizeof($filename_array)-1];

		$user->name = Input::get('name');
		if(Input::get('imgName') != "") {
			$oldPic = $user->picture;
			$oldPicEdited = substr($oldPic,1);
			\File::delete($oldPicEdited);
			$user->picture = '/uploads/'.$filename;;
		}

		if($user->save()){
			rename('temp/'.$filename,'uploads/'.$filename);
			Session::flash('notice','Sucess');
			return redirect('/');
		}
	}

	public function delete($id){
		$user = User::findOrFail($id);
		$oldPic = $user->picture;
		$oldPicEdited = substr($oldPic,1);
		\File::delete($oldPicEdited);
		if($user->delete()){
			File::delete($oldPic);
			Session::flash('notice','Deleted!');
			return redirect('/');
		}
	}


}
