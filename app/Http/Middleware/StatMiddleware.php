<?php

namespace App\Http\Middleware;

use Closure,
    Cookie,
    LavarelRedis,
    GeoIP;
use Predis\Pipeline\Pipeline;

class StatMiddleware
{
    /**
     * @var \Illuminate\Support\Facades\Redis
     */
    protected $redis;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $page = '';

    /**
     * @var array
     */
    var $options = array(
        'ip' => '',
        'os' => '',
        'browser' => '',
        'country' => '',
        'ref' => ''
    );

    /**
     * New instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->redis = LavarelRedis::connection();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;
        
        $this->page = $request->path();

        $this->_pushStat();

        //dd($request);
        
        return $next($request);
    }

    /**
     * Push new rows to stats
     *
     * @return void
     */
    protected function _pushStat()
    {

        $this->_detectRefIP();

        $this->_detectOS();

        $this->_detectBrowser();

        $this->_detectCountry();

        $this->_detectRef();

        // push to redis
        $this->redis->pipeline(function($pipe)
        {
            //Page to set of pages
            $pipe->sadd('gstat:pages', $this->page);

            //Site
            $this->_keyPipeSend($pipe, 'gstat:site');

            //Page
            $this->_keyPipeSend($pipe, 'gstat:page:'.$this->page);


        });

    }

    /**
     * Helps to send all needed pipe commands for one key
     *
     * @param $pipe Pipeline
     * @param $key string
     */
    protected function _keyPipeSend($pipe, $key){

        //safe key
        $key = $this->_removeSpaces($key);

        /*
         * Common for instance
         */
        //Views
        $pipe->hIncrBy($key, 'views', 1);

        //Unique views
        if ( !LavarelRedis::sIsMember($key.':ip', $this->options['ip']) )
        {
            $pipe->hIncrBy($key, 'viewsip', 1);

            //Add $ip to set
            $pipe->sadd($key.':ip', $this->options['ip']);
        }

        //Cookie views
        if ( !$this->_hasCookie($key) )
        {
            $pipe->hIncrBy($key, 'viewscookie', 1);
        }


        /*
         * Column dependent
         */
        foreach($this->options as $name => $value)
        {
            $optkey = $this->_removeSpaces($key . ':' . $name . ':' .$value);

            //Views
            $pipe->hIncrBy($optkey, 'views', 1);

            //Unique views
            $key_exists = LavarelRedis::exists($key.':'.$name);
            if ( !$key_exists || ( $key_exists && !LavarelRedis::sIsMember($key.':'.$name, $this->options[$name])))
            {
                $pipe->hIncrBy($optkey, 'viewsip', 1);

                //Add $ip to set
                $pipe->sadd($key.':'. $name, $this->options[$name]);
            }

            //Cookie views
            if ( !$this->_hasCookie($key) )
            {
                $pipe->hIncrBy($optkey, 'viewscookie', 1);
            }

        }

    }

    /**
     * Get Visitor IP
     *
     * @return void
     */
    protected function _detectRefIP()
    {
        if (!empty($this->request->server('HTTP_CLIENT_IP')))   //check ip from share internet
        {
            $ip = $this->request->server('HTTP_CLIENT_IP');
        }
        elseif (!empty($this->request->server('HTTP_X_FORWARDED_FOR')))   //to check ip is pass from proxy
        {
            $ip = $this->request->server('HTTP_X_FORWARDED_FOR');
        }
        else
        {
            $ip = $this->request->server('REMOTE_ADDR');
        }

        $this->options['ip'] = $ip;
    }

    /**
     * Get Visitor OS
     *
     * @return void
     */
    protected function _detectOS()
    {

        $os_platform    =   "Unknown OS Platform";

        $os_array       =   array(
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value)
        {

            if (preg_match($regex, $this->request->server('HTTP_USER_AGENT')))
            {
                $os_platform    =   $value;
            }

        }

        $this->options['os'] = $os_platform;

    }

    /**
     * Get Visitor Browser
     *
     * @return void
     */
    protected function _detectBrowser()
    {

        $browser        =   "Unknown Browser";

        $browser_array  =   array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value)
        {

            if (preg_match($regex, $this->request->server('HTTP_USER_AGENT')))
            {
                $browser    =   $value;
            }

        }

        $this->options['browser'] = $browser;

    }

    /**
     * Get Visitor Country
     *
     * @return void
     */
    protected function _detectCountry()
    {

        if (!empty($this->options['ip']))
        {
            $location = GeoIP::getLocation($this->options['ip']);
        }
        else
        {
            $location = GeoIP::getLocation();
        }

        $this->options['country'] = $location['country'];

    }

    /**
     * Get Visitor Referer
     *
     * @return void
     */
    protected function _detectRef()
    {

        $this->options['ref'] = !empty($this->request->server('HTTP_REFERER')) ? parse_url($this->request->server('HTTP_REFERER'), PHP_URL_HOST) : 'No referer';

    }

    /**
     * Get Visitor Cookie existence
     *
     * @return boolean
     */
    protected function _hasCookie($cookie_name)
    {

        if (!$this->request->hasCookie($cookie_name))
        {
            Cookie::queue(Cookie::forever($cookie_name, 1)); //Cookie::forever($cookie_name, '1');
            return false;
        }
        else
        {
            return true;
        }

    }

    /**
     * Removes spaces for keys
     *
     * @param $string
     * @return mixed
     */
    protected function _removeSpaces($string){
        return preg_replace('/\s/U', '_', $string);
    }

}
