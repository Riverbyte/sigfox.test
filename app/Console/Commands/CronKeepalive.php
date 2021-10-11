<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Device;

use Illuminate\Console\Command;

class CronKeepalive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:keepalive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron para validar el keep Alive';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $e_TEXTO ='';
        $r_devices = DB::table('devices')->get();
        foreach ($r_devices as $a_device) {
            $message = DB::table('messages')->select('time')->where('device_id', $a_device->id)->whereRaw('time >= DATE_SUB(NOW(),INTERVAL 1 DAY)')->orderBy('id', 'desc')->first();

            $device = Device::find($a_device->id);

            if($message)
            {
                $a_AVISO = array();
                if($device->alert_json)
                {
                    $a_AVISO = json_decode($device->alert_json,1);
                }
                if(in_array('last_keepAlive',$a_AVISO))
                {
                    $e_KEY = array_search('last_keepAlive',$a_AVISO);
                    unset($a_AVISO[$e_KEY]); 
                    unset($a_AVISO[$e_KEY + 1]); 
                    if(count($a_AVISO) > 0 )
                    {
                        $device->update([
                            'alert_json' => json_encode($a_AVISO)
                        ]);
                    }
                    else
                    {
                        $device->update([
                            'alert' => 0,
                            'alert_json' => json_encode($a_AVISO)
                        ]);

                    }

                    Storage::append('archivo.txt', "a_AVISO  -> " .  json_encode($a_AVISO) . "\n");
                }

                Storage::append('archivo.txt', '['. date('Y-m-d') . "]: {$a_device->id} message:  {$message->time} \n");
            }
            else
            {
                $last_message = DB::table('messages')->select('time')->where('device_id', $a_device->id)->orderBy('id', 'desc')->first();


                $a_AVISO = array();
                if($device->alert_json)
                {
                    $a_AVISO = json_decode($device->alert_json,1);
                }
                

                if(!in_array('last_keepAlive',$a_AVISO) || count($a_AVISO) == 0 )
                {
                    $a_AVISO[] = 'last_keepAlive';
                    if($last_message)
                        $a_AVISO[] = $last_message->time;
                    else
                    $a_AVISO[] = '0';

                    $device->update([
                        'alert' => 1,
                        'alert_json' => json_encode($a_AVISO)
                    ]);

                }

            }
            

            
        }

        
        
        
    }
}
