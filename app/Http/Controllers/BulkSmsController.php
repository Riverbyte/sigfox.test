<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Validator;

class BulkSmsController extends Controller
{
    public function sendSms($numbers, $message)
    {
       // Your Account SID and Auth Token from twilio.com/console
       $sid    = env( 'TWILIO_SID' );
       $token  = env( 'TWILIO_TOKEN' );
       $client = new Client( $sid, $token );



       $numbers_in_arrays = explode( ',' , $numbers );

       $message = $message;
       $count = 0;

       foreach( $numbers_in_arrays as $number )
       {
           $count++;

           $client->messages->create
           (
                $number,
                [
                    'from' => env( 'TWILIO_FROM' ),
                    'body' => $message,
                ]
            );
        }

   }


   public function call($numbers, $message)
   {

       // Your Account SID and Auth Token from twilio.com/console
       $sid    = env( 'TWILIO_SID' );
       $token  = env( 'TWILIO_TOKEN' );
       $from   = env( 'TWILIO_FROM' );
       $client = new Client( $sid, $token );

       // Use the Twilio-provided site for the TwiML response.
       $url = route('callmessage');

       $numbers_in_arrays = explode( ',' , $numbers );

       $message = $message;
       $count = 0;

       foreach( $numbers_in_arrays as $number )
       {
           $count++;

           $client->calls->create
           (
                $number,
                $from,
                array(
                    "url" => $url.'?Message='.urlencode($message)
                )
            );
        }

   }
}
