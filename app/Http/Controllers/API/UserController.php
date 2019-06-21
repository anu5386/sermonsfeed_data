<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use RecursiveIteratorIterator;
use RecursiveArrayIterator;


class UserController extends Controller
{
public $successStatus = 200;
/**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
/**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
$input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        $success['name'] =  $user->name;
return response()->json(['success'=>$success], $this-> successStatus);
    }
/**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
    }



public function sermonsData() {
  /*  $demodata=[
      'id' => '1'
    ];
     return response()->json($demodata);*/

      $fileContents= file_get_contents('http://127.0.0.1:8080/api/sermons/list');
     //$fileContents = str_replace(array("\n", "\r", "\t"), '', $url);
     $json = json_decode($fileContents,true);

     echo "<table border='2' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber1'>";
     //'<table border="1" >';
     echo "<tr>";
     echo "<th>TITLE</th>";
     echo "<th>VIDEO</th>";
     echo "<th>YOUTUBE LINK</th>";

     //echo "<th>PLAYBACK SOURCE</th>";
     echo "<th>POSTER IMAGE</th>";

     echo "<th>LINK</th>";
     echo "<th>SPEAKER</th>";
     echo "<th>DATE</th>";
     echo "<th>VIEWS</th>";
     echo "</tr>";



     foreach($json["channel"]["item"] as $item)
     {

       $title = $item['title'] . '<br />';
       //$description = $item['description'] . '<br />';
       $video = $item['video'] . '<br />';
       $youtubeID = $item['youtubeID'] . '<br />';
       $posterImage = $item['posterImage'] . '<br />';
       //$playbackSource = $item['playbackSource'] . '<br />';
       $link = $item['link'] . '<br />';
       $speaker = $item['speaker'] . '<br />';
       $date = $item['date'] . '<br />';
       $dateformat=Date("Y-m-d",strtotime($date));
       $views= $item['views'] . '<br />';
      // $audio = $item['audio'] . '<br />';


       echo "<tr>";
       echo "<td>$title</td>";
       //echo "<td>$description</td>";
       echo "<td><a href='$video'>$video</a></td>";
       echo "<td><a href='$youtubeID'>$youtubeID</a></td>";

       //echo "<td><iframe src="$youtubeID">$youtubeID</iframe></td>";
       echo "<td><a href='$posterImage'>$posterImage</a></td>";
       echo "<td><a href='$link'>$link</a></td>";
       echo "<td>$speaker</td>";
       echo "<td>$dateformat</td>";
       echo "<td>$views</td>";

      // echo "<td>$playbackSource</td>";
       echo "</tr>";


     }
     echo "</table>";



   }

}
