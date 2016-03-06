<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use League\Flysystem\File;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CropController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getHome()
	{
		return view('index');
	}

	public function postUpload(){
		$form_data = Input::all();

		$validator = Validator::make($form_data, User::$rules, User::$messages);

		if ($validator->fails()) {

			return \Response::json([
				'status' => 'error',
				'message' => $validator->messages()->first(),
			], 200);

		}

		$photo = $form_data['img'];

		if(filesize($photo)==0 || filesize($photo)>250000){
			return \Response::json([
				'status' => 'error',
				'message' => 'Max size is 250 KB',
			], 200);
		}

		$original_name = $photo->getClientOriginalName();
		$original_name_without_ext = substr($original_name, 0, strlen($original_name) - 4);

		$filename = $this->sanitize($original_name_without_ext);
		$allowed_filename = $this->createUniqueFilename( $filename );

		$filename_ext = $allowed_filename . date('His') .'.jpg';

		$manager = new ImageManager();
		$image = $manager->make( $photo )->encode('jpg')->save('temp/'.$filename_ext );

		if( !$image) {

			return \Response::json([
				'status' => 'error',
				'message' => 'Server error while uploading',
			], 200);

		}

//		$database_image = new User();
//		$database_image->name      = "Image";
//		$database_image->picture      = $allowed_filename;
//		$database_image->original_name = $original_name;
//		$database_image->save();

		return \Response::json([
			'status'    => 'success',
			'url'       => '/temp/' . $filename_ext,
			'width'     => $image->width(),
			'height'    => $image->height()
		], 200);
	}

	public function postCrop(){
		$form_data = Input::all();
		$image_url = $form_data['imgUrl'];

		// resized sizes
		$imgW = $form_data['imgW'];
		$imgH = $form_data['imgH'];
		// offsets
		$imgY1 = $form_data['imgY1'];
		$imgX1 = $form_data['imgX1'];
		// crop box
		$cropW = $form_data['width'];
		$cropH = $form_data['height'];
		// rotation angle
		$angle = $form_data['rotation'];

		$filename_array = explode('/', $image_url);
		$filename = $filename_array[sizeof($filename_array)-1];

		$manager = new ImageManager();
		$image = $manager->make( 'temp/'.$filename );
		$image->resize($imgW, $imgH)->rotate(-$angle)->crop($cropW, $cropH, $imgX1, $imgY1)->save('temp/cropped-' . $filename);
		\File::delete('temp/'.$filename);
		if( !$image) {

			return Response::json([
				'status' => 'error',
				'message' => 'Server error while uploading',
			], 200);

		}

		return \Response::json([
			'status' => 'success',
			'url' => '/temp/cropped-' . $filename
		], 200);
	}

	private function sanitize($string, $force_lowercase = true, $anal = false)
	{
		$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
			"}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
			"â€”", "â€“", ",", "<", ".", ">", "/", "?");
		$clean = trim(str_replace($strip, "", strip_tags($string)));
		$clean = preg_replace('/\s+/', "-", $clean);
		$clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;

		return ($force_lowercase) ?
			(function_exists('mb_strtolower')) ?
				mb_strtolower($clean, 'UTF-8') :
				strtolower($clean) :
			$clean;
	}


	private function createUniqueFilename( $filename )
	{
//		$upload_path = env('UPLOAD_PATH');
		$full_image_path = 'temp/' . $filename . '.jpg';

		if ( \Illuminate\Support\Facades\File::exists( $full_image_path ) )
		{
			// Generate token for image
			$image_token = substr(sha1(mt_rand()), 0, 5);
			return $filename . '-' . $image_token;
		}

		return $filename;
	}

}
