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
        
        $uid = $request->input('uid');
        $to = $request->input('to');
        $message = $request->input('message');
        $sender_id = $request->input('sender_id');
        $provider = $request->input('provider');
        $callback = $request->input('callback');
        
        $request_id = $this->random_num(12);
        
        $sms = new SMSModel();
        
        $sms->uid = $uid;
        $sms->request_id = $request_id;
        $sms->to = $to;
        $sms->message = $message;
        $sms->provider = $provider;
        $sms->sender_id = $sender_id;
        $sms->callback = $callback;

        if(empty($to)){
            $sms->status = 'fail';
            $sms->error = 'Empty Mobile Number';

            $sms->save();

            return response()->json(['status' => 'Fail', 'message' => 'Empty Mobile Number', 'data' => ['request_id' => $request_id, 'uid' => $uid] ]);
        }

        if(empty($message)){
            $sms->status = 'fail';
            $sms->error = 'Empty Message';

            $sms->save();

            return response()->json(['status' => 'Fail', 'message' => 'Empty Message', 'data' => ['request_id' => $request_id, 'uid' => $uid] ]);
        }

        if(empty($sender_id)){
            $sms->status = 'fail';
            $sms->error = 'Empty Sender ID';

            $sms->save();

            return response()->json(['status' => 'Fail', 'message' => 'Empty Sender ID', 'data' => ['request_id' => $request_id, 'uid' => $uid] ]);
        }

        if(empty($provider)){
            $sms->status = 'fail';
            $sms->error = 'Empty Provider';

            $sms->save();

            return response()->json(['status' => 'Fail', 'message' => 'Empty Provider', 'data' => ['request_id' => $request_id, 'uid' => $uid] ]);
        }

        if(empty($callback)){
            $sms->status = 'fail';
            $sms->error = 'Empty Callback URI';

            $sms->save();

            return response()->json(['status' => 'Fail', 'message' => 'Empty Callback URI', 'data' => ['request_id' => $request_id, 'uid' => $uid] ]);
        }

        if(!\preg_match('/^[7-9]{1}[0-9]{9}$/', $to)){
            $sms->status = 'fail';
            $sms->error = 'Invalid Mobile Number';

            $sms->save();

            return response()->json(['status' => 'Fail', 'message' => 'Invalid Mobile Number', 'data' => ['request_id' => $request_id, 'uid' => $uid] ]);
        }

        $sms->status = 'success';

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
