<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use LavarelRedis;

class DashboardController extends Controller
{
    /**
     * @var \Illuminate\Support\Facades\Redis
     */
    protected $redis;

    /**
     * @var array
     */
    private static $browsers;

    /**
     * @var array
     */
    private static $os;

    /**
     * @var array
     */
    private static $countries;

    /**
     * @var array
     */
    private static $pages;

    /**
     * @var array
     */
    private static $refs;

    /**
     * DashboardController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redis = LavarelRedis::connection();

        if (empty(self::$browsers))
        {
            self::$browsers = $this->_retrieveSetData('gstat:site:browser');
        }

        if (empty(self::$os))
        {
            self::$os = $this->_retrieveSetData('gstat:site:os');
        }

        if (empty(self::$countries))
        {
            self::$countries = $this->_retrieveSetData('gstat:site:country');
        }

        if (empty(self::$refs))
        {
            self::$refs = $this->_retrieveSetData('gstat:site:ref');
        }

        if (empty(self::$pages))
        {
            self::$pages = $this->_retrieveSetData('gstat:pages');
        }

    }

    /**
    * Display a listing of the posts.
    *
    * @return Response
    */
    public function index()
    {
        $data = array();

        //Site totals
        $data['site'] = $this->_prepareData('gstat:site');

        //Pages
        foreach(@$this::$pages as $page)
        {
            $data['pages'][$page] = $this->_prepareData('gstat:page:'.$page);
        }

        return view('admin.dashboard.index', $data);
    }

    /**
     * Display a listing of the posts.
     *
     * @param $page string
     * @return Response
     */
    public function pageStat($alias)
    {
        $data = $this->_prepareData('gstat:page:page/'.$alias);

        $data['page'] = 'page/' . $alias;

        return view('admin.dashboard.page', $data);
    }

    /**
     * @param $key string
     * @return mixed
     */
    protected function _prepareData($key)
    {
        $return = array();

        //totals
        $return['totals'] = $this->_retrieveHashData($key);

        //browser
        foreach(@self::$browsers as $name){
            $optkey = $key . ':browser:' . $name;
            $return['browser'][$name] = $this->_retrieveHashData($optkey);
        }

        //os
        foreach(@self::$os as $name){
            $optkey = $key . ':os:' . $name;
            $return['os'][$name] = $this->_retrieveHashData($optkey);
        }

        //country
        foreach(@self::$countries as $name){
            $optkey = $key . ':country:' . $name;
            $return['country'][$name] = $this->_retrieveHashData($optkey);
        }

        //refs
        foreach(@self::$refs as $name){
            $optkey = $key . ':ref:' . $name;
            $return['ref'][$name] = $this->_retrieveHashData($optkey);
        }

        return $return;

    }

    /**
     * Get array of strings from redis
     *
     * @param $key string
     */
    protected function _retrieveSetData($key)
    {

        return $this->redis->sMembers($key);

    }

    /**
     * Get data from redis for key
     * 
     * @param $key
     */
    protected function _retrieveHashData($key)
    {
        return $this->redis->hGetAll($key);
    }
}
