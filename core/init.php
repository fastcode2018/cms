<?php
    require_once 'config.php';
    require REAL_DIR . '/vendor/autoload.php';
    use \library as lib;

    $sys = new lib\System();

    $sys->Get('/',function($controller,$args,$data){
        $controller->import('about.html',$args,$data);
    });

    $sys->Get('/about',function($controller,$args,$data){
        $controller->import('about.html',$args,$data);
    });

    $sys->Get('/action',function($controller,$args,$data){
        if(isset($data['submit']))
        {
            switch($data['type'])
            {
                case 'register':
                    $username = $data['username'];
                    $email = $data['email'];
                    $password = $data['password'];
                    $repeat_password = $data['repeat-password'];
                    //return $username;
                break;
                case 'auth':

                break;
                case 'edit':

                break;
                case 'delete':

                break;                
                default:

                break;
            }
        }
    }); 

    $sys->Default(function($controller,$args,$data){
        $controller->import('pages/404.html',$args,$data);
    });

    //$test = $sys->Query('SELECT * FROM users');
    //print_r($test);
    $vaildator = new lib\Vaildator;

    $vaild = $vaildator->Check([
        [
            'attr' => 'usermame',
            'value' => '',
            'fitler' => 'required,max=255,min=6'
        ]
    ]);
    print_r($vaild); 
?>