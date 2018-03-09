<?php
    namespace library;

    class Boostrap extends System {

        function __construct()
        {
            
        }

        function Record($s,$path,$func)
        {
            $Args = $this->GetArguments($path);
            $Data = $this->GetArguments($path,'POST');

            $RawCode = $func($s,$Args,$Data);

            if($RawCode != NULL)
                echo $RawCode;
        }
    }
?>