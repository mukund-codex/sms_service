<?php

namespace App\Jobs;

use App\Models\SMSQueueModel;
use App\Models\SMSLogModel;
use App\Helpers\Common;
use Illuminate\Support\Facades\Log;

class SMSSender extends Job
{

    public $sms_queue_id;
    protected $logger;
    protected $common;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sms_queue_id, Log $logger, Common $common)
    {
        //        

        $this->sms_queue_id = $sms_queue_id;
        $this->logger = $logger;
        $this->common = $common;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->logger->info('Job - sms_queue_id: '.$this->sms_queue_id);
        $data = [];
        $uid;
        $queuerecord = SMSQueueModel::find(['queue_id' => $this->sms_queue_id])->first();

        $data['request_id'] = $request_id = $queuerecord->request_id;
        $data['uid'] = $uid = $queuerecord->uid;
        $data['to'] = $to = $queuerecord->to;
        $data['message'] = $message = $queuerecord->message;
        $data['sender_id'] = $sender_id = $queuerecord->sender_id;
        $data['provider'] = $provider = $queuerecord->provider;
        $data['callback'] = $callback = $queuerecord->callback;

        switch($provider){
			case 'quicksmart':
				$username = 'Techizer';
				$key = '92Ay3difxPE2';
				
				$master_url = 'http://quicksmart.in/api/pushsms?user=' . $username . '&authkey=' . $key . '&sender=' . $sender_id;
				$is_english = is_english($message);
				$url = $master_url . '&mobile=' . $to . '&text=' . urlencode($message) . '&rpt=1';
				if (! $is_english){
					$url .= '&type=1';
				}
					
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //$output = curl_exec($ch);
                $output = 'success';
				$pos = strpos($output,"STATUS:OK");
				$is_success = ($pos == true) ? 1 : 0;
				curl_close($ch);

				break;
			case 'meru': 
				break;
			default:
                $message = urlencode($message);
			
				$url = "http://alerts.sinfini.com/api/web2sms.php?workingkey=79205ve7suw5bj1odtr5&sender=".$sender_id."&to=$to&message=$message";
		
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //$output = curl_exec($ch);
                $output = 'success';
		
				$pos = strpos($output,"GID=");
				$is_success = ($pos == true) ? 1 : 0;
				curl_close($ch); 
				   
				break;
        }

        $data['is_success'] = $is_success;
        $data['output'] = $output = $pos;     

        $smslog = new SMSLogModel();
        $smslog->uid = $uid;
        $smslog->request_id = $request_id;
        $smslog->to = $to;
        $smslog->message = $message;
        $smslog->sender_id = $sender_id;
        $smslog->callback = $callback;
        $smslog->provider = $provider;
        $smslog->is_success = $is_success;
        $smslog->output = $output;

        $smslog->save();

        $request = $this->common->curl_request($callback, $data);

        \dd($request);

    }

    function is_english($str) {
        if (strlen($str) != strlen(utf8_decode($str))) {
            return false;
        }
            return true;
        
    }
}
