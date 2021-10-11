<?php


if (! function_exists('procesa_datos')) {
    function procesa_datos($message)
    {
        $a_RETORNO = array();
        
        $a_CONFIG_MODE = array(
            '1' => 'Timer',
            '2' => 'Event',
            '3' => 'Timer + Event',
            '4' => 'Event + Heartbeat'
        );

        $a_CONFIG_MODE_SENS = array(
            '1' => 'Low',
            '2' => 'Mid',
            '3' => 'High+Event'                   
        );


        $a_DATA = str_split($message->DATA, 2);
        $e_SIZE = count($a_DATA);

        if(str_contains(strtolower($message->NAME), 'bell'))
        {
            if($e_SIZE == 2)
            {
                $a_BELL_MODE = array(
                    '01' => 'TIMER',
                    '02' => 'EVENT'
                );
        
                $a_BELL_STATE = array(
                    '00' => 'SHORT PRESS',
                    '01' => 'LONG PRESS',
                );

                $a_RETORNO[] = 'MODE: '. $a_BELL_MODE[$a_DATA[0]] . PHP_EOL;

                if($a_DATA[0] == '01')
                {
                    $a_RETORNO[] = 'INTERVAL: '. $a_DATA[1];
                }
                else
                {
                    $a_RETORNO[] = 'STATE: '. $a_BELL_STATE[$a_DATA[1]];
                }
            }
                
        }




        if(str_contains(strtolower($message->NAME), 'motion'))
        {
            if($e_SIZE == 2)
            {
                $a_BELL_MODE = array(
                    '01' => 'TIMER',
                    '02' => 'EVENT'
                );
        
                $a_BELL_STATE = array(
                    '01' => 'MOTION DETECTED',
                );

                $a_RETORNO[] = 'MODE: '. $a_BELL_MODE[$a_DATA[0]] . PHP_EOL;

                if($a_DATA[0] == '01')
                {
                    $a_RETORNO[] = 'INTERVAL: '. $a_DATA[1];
                }
                else
                {
                    $a_RETORNO[] = 'STATE: '. $a_BELL_STATE[$a_DATA[1]];
                }
            }                
        }


        if(str_contains(strtolower($message->NAME), 'protect'))
        {
            if($e_SIZE == 2)
            {
                $a_BELL_MODE = array(
                    '01' => 'TIMER',
                    '02' => 'EVENT'
                );
        
                $a_BELL_STATE = array(
                    '01' => 'CLOSED',
                    '02' => 'OPENED',
                );

                $a_RETORNO[] = 'MODE: '. $a_BELL_MODE[$a_DATA[0]] . PHP_EOL;

                if($a_DATA[0] == '01')
                {
                    $a_RETORNO[] = 'INTERVAL: '. $a_DATA[1];
                }
                else
                {
                    $a_RETORNO[] = 'STATE: '. $a_BELL_STATE[$a_DATA[1]];
                }
            }    
        }


        
        if(str_contains(strtolower($message->NAME), 'sense'))
        {
            if($e_SIZE == 10)
            {
                $a_BELL_MODE = array(
                    '01' => 'TIMER',
                    '02' => 'EVENT'
                );
        
                $a_RETORNO[] = 'MODE: '. $a_BELL_MODE[$a_DATA[0]] . PHP_EOL;

                if($a_DATA[0] == '01')
                {
                    $a_RETORNO[] = 'INTERVAL: '. round((86400 / hexdec($a_DATA[1])) / 60) . ' min' . PHP_EOL;
                    $a_RETORNO[] = 'TEMPERATURE 1/2: '. hexdec($a_DATA[2].$a_DATA[3]) / 100 . ' degree' . PHP_EOL;
                    $a_RETORNO[] = 'TEMPERATURE 2/2: '. hexdec($a_DATA[4].$a_DATA[5]) / 100 . ' degree' . PHP_EOL;

                    $a_RETORNO[] = 'REL.HUMIDITY 1/2: '. hexdec($a_DATA[6].$a_DATA[7]) / 100 . '%' . PHP_EOL;
                    $a_RETORNO[] = 'REL.HUMIDITY 2/2: '. hexdec($a_DATA[8].$a_DATA[9]) / 100 . '%' . PHP_EOL;
                }
                else
                {
                    $a_RETORNO[] = 'TEMPERATURE 1/2: '. hexdec($a_DATA[1].$a_DATA[2]) / 100 . ' degree' . PHP_EOL;

                    $a_RETORNO[] = 'REL.HUMIDITY 1/2: '. hexdec($a_DATA[3].$a_DATA[4]) / 100 . '%' . PHP_EOL;
                }
            }    
        }




//////////////////////////////////////////  Heartbeat
        if($a_DATA[0] == '04' )
        {
            

            $a_CONFIG_INTERVAL = array(
                '1' => 'Low',
                '2' => 'Mid',
                '3' => 'High+Event'                   
            );


            $a_RETORNO[] = 'VOLTAGE: '. hexdec($a_DATA[1].$a_DATA[2]) / 1000 . 'v' . PHP_EOL;
            $a_CONFIG = str_split($a_DATA[3]);
            $a_RETORNO[] = 'CONFIG MODE: '. $a_CONFIG_MODE[$a_CONFIG[0]] . ' Sensitivit: ' . $a_CONFIG_MODE_SENS[$a_CONFIG[1]] . PHP_EOL;
            $a_RETORNO[] = 'INTERVAL: '. round((86400 / hexdec($a_DATA[4])) / 60) . ' min' . PHP_EOL;
            
        }


        
//////////////////////////////////////////  GENERIC
        if($a_DATA[0] == '00' && $e_SIZE <= 10 && ctype_xdigit($message->DATA) )
        {
            $a_HW_VERSION_MAJOR = array(
                '01' => 'UNABELL',
                '02' => 'UNASENSE',
                '03' => 'UNAMOTION',
                '04' => 'UNAPROTECT',
                '05' => 'UNABEACON',
            );

            $a_HW_VERSION_MINOR = array(
                '00' => 'UnaSensorV0',
                '01' => 'UnaBellV1M1',
                '02' => 'UnaBellV1M2',
                '03' => 'UnaSensorBellV0',
                '04' => 'UnaSensorV1'
            );

            


            $a_RETORNO[] = 'VOLTAGE: '. hexdec($a_DATA[1].$a_DATA[2]) / 1000 . 'v' . PHP_EOL;
            $a_RETORNO[] = 'HW Version: '. $a_HW_VERSION_MAJOR[$a_DATA[3]] . ' - ' . $a_HW_VERSION_MINOR[$a_DATA[4]] . PHP_EOL;

            $a_CONFIG = str_split($a_DATA[7]);

            $a_RETORNO[] = 'CONFIG MODE: '. $a_CONFIG_MODE[$a_CONFIG[0]] . ' Sensitivit: ' . $a_CONFIG_MODE_SENS[$a_CONFIG[1]] . PHP_EOL;

            $a_RETORNO[] = 'INTERVAL: '. round((86400 / hexdec($a_DATA[8])) / 60) . ' min' . PHP_EOL;
        }
        else
        {
            $a_RETORNO[] = $message->DATA;
        }


        return $a_RETORNO;
        
    }
}