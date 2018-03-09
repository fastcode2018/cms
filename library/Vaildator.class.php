<?php
    namespace library;

    /**
     * Errors:
     * 0: Required
     * 1: min
     * 2: max
     * 3: unique
     * .........
     */

    class Vaildator {

        private $errors;
        private $error_messages = [

            0 => '{{error_messages[0]}} is Required!',
            1 => '{{d["attr"]}} is Too Short!',
            2 => '{{d["attr"]}} is Too Long!',
            3 => '{{d["attr"]}} must be unique from {{fitler[1]}}!'
        ];


        # Temp Variables
        private $d;
        private $fitler;
        
        public function Check($raw_data)
        {
            $this->d = $this->fitler = $this->errors = $data = [];
            if(is_array($raw_data))
            {
                # Explode Raw Text to Array
                foreach($raw_data as $each){
                    $temp = ['value'=>$each['value'],'fitler'=>explode(',',$each['fitler'])];
                    for($i = 0;$i<count($temp['fitler']);$i++)
                        $temp['fitler'][$i] = explode('=',$temp['fitler'][$i]);
                    $data[] = $temp;

                }
                foreach($data as $d)
                {
                    $this->d = $d;
                    foreach($d['fitler'] as $fitlers)
                        foreach($fitlers as $fitler)
                        {
                            $this->fitler = $fitler;
                            switch($fitler)
                            {
                                case 'required':
                                    if(strlen($d['value']) <= 0)
                                        $errors[] = $this->AddError(0);
                                break;
                                case 'min':
                                    if(strlen($d['value']) < (int) $fitler[1])
                                        $errors[] = $this->AddError(1);
                                break;
                                case 'max':
                                    
                                    if(strlen($d['value']) > (int) $fitler[1])
                                        $errors[] = $this->AddError(2);                               
                                break;
                                case 'unique':
                                    if(strpos($fitler[1],$d['value']) > -1)
                                        $errors[] = $this->AddError(3);
                                break;
                            }
                        }
                }

                
                print_r($data);

                return $errors;
            }
            else
                return false;
        }

        function AddError($id)
        {
            /*$vars;
            $res = $this->error_messages[$id];
            preg_match_all('/{{(.*)}}/',$res,$vars, PREG_OFFSET_CAPTURE);
            foreach($vars[1] as $var)
            {
                print_r($var[0]);
                echo '<br/>';
                //$res = str_replace('{{'.$var[0].'}}',$this->$var[0],$res);
            }
            return $res;*/
            return $id;
        }
    }
?>