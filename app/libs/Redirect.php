<?php 

namespace cgc\platform\libs;


class Redirect 
{

    public static function path($path)
    {
        return '<script type="text/javascript">
        window.location.href="/'.$path.'";</script>';
    }
}