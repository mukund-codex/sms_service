<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SMSModel;
use App\Models\SMSQueueModel;
use App\Jobs\SMSSender;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Classes\ErrorsClass;
use App\Events\SMSEvent;

class SMSController extends Controller
{   
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $request_id = $this->random_num(12);

        $validation = $this->validation($request, $request_id);

        $uid = $request->input('uid');
        $to = $request->input('to');
        $message = $request->input('message');
        $sender_id = $request->input('sender_id');
        $provider = $request->input('provider');
        $callback = $request->input('callback');
        
        $sms = new SMSModel();
        
        $sms->uid = $uid;
        $sms->request_id = $request_id;
        $sms->to = $to;
        $sms->message = $message;
        $sms->provider = $provider;
        $sms->sender_id = $sender_id;
        $sms->callback = $callback;        
        $sms->status = json_encode($validation);

        $sms->save();
        
        $smsQueue = new SMSQueueModel();
        $smsQueue->uid = $uid;
        $smsQueue->request_id = $request_id;
        $smsQueue->to = $to;
        $smsQueue->message = $message;
        $smsQueue->provider = $provider;
        $smsQueue->sender_id = $sender_id;
        $smsQueue->callback = $callback;
        
        $smsQueue->save();
        
        //\dd($smsQueue);
        event(new SMSEvent($smsQueue->queue_id));
        //\dispatch(new SMSSender($smsQueue->queue_id));

    }

    public function validation($request, $request_id){

        //
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'to' => 'required|numeric',
            'message' => 'required',
            'sender_id' => 'required',
            'provider' => 'required',
            'callback' => 'required',
        ]);
        
        $errors = $validator->errors()->messages();
        
        $status = empty($errors) ? 'Success' : 'Fail';

        return response()->json(['status' => $status, 'message' => $errors, 'data' => ['request_id' => $request_id]]);

    }

    public function random_num($size) {
        $alpha_key = '';
        $keys = range('A', 'Z');
        
        for ($i = 0; $i < 2; $i++) {
            $alpha_key .= $keys[array_rand($keys)];
        }
        
        $length = $size - 2;
        
        $key = '';
        $keys = range(0, 9);
        
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        
        return $alpha_key . $key;
    }

}
