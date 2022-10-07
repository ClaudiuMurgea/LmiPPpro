<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
class Interactive extends Component
{
    public $isLoading               = true;
    public $menu                    = true;
    public $showReturn              = true;
    public $showSystemJackpots      = true;
    public $showExternalJackpots    = true;
    public $initial                 = true;
    public $getExternalJackpots     = false;
    public $getInternalJackpots     = false;
    public $cashouts                = false;
    public $jackpots                = false;
    public $showJackpot             = false;
    public $jackpotValue;
    public $jackpotTitle;
    public $mac;       
    public function mount()
    {
        // Getting the MAC from the URL, which is sent through ajax request from 'resources/views/livewire/application-livewire'
        $mac = $_GET["MAC"];
        $this->mac = str_replace('"', "", $mac);

        // Saving the mac inside a cookie if the cookie is not set (The cookie is used inside 'app/Http/Controllers/PidController.php')
        if(!Cookie::get('LmiMacNew')){
            Cookie::queue('LmiMacNew', $this->mac, 2147483647);
        }
    }
    public function render()
    {
        // If the user is on Cashouts page, query the database for the $cashoutValues, otherwise just send a empty string to the Livewire-View-Component
        $cashoutValues = '';
        if($this->cashouts)
        {
            try {
                $getDeviceID =  DB::connection('mysql_main')->select("SELECT DeviceID FROM lmi.devices_last WHERE MAC='$this->mac'");
                $DeviceID = $getDeviceID[0]->DeviceID;
            } catch (\Illuminate\Database\QueryException $e) {
                return $e->getMessage();
            }
            $getCashouts= DB::connection('mysql_main')->select("SELECT Slot,FORMAT((Value/100),2) Value,Timestamp FROM lmi.Handpays WHERE DeviceID=$DeviceID ORDER BY `Timestamp` DESC LIMIT 10");
                $cashoutValues = $getCashouts;
        }

        // If the user is on Jackpots page, query the database for system and external jackpots
        if($this->jackpots) 
        {
            $this->getExternalJackpots = DB::connection('mysql_main')->select("call LmiPP.GetSystemJackpotsNames");
            $this->getInternalJackpots = DB::connection('mysql_main')->select("call LmiPP.GetExternalJackpotsNames");
        }

        $getPID = DB::connection('mysql_main')->select("select LmiPP.MAC2PID('".$this->mac."') PID");
        // $activePID = $getPID[0]->PID;
        // if($activePID > 0) 
        // {
        //     redirect("/");
        // }
        return view('livewire.interactive', ['cashoutValues' => $cashoutValues]);
    }

    // When the user clicks on one menu icon, this function is triggered
    public function showThis($page) 
    {
        $this->$page        = true;
        $this->initial      = false;
        $this->menu         = false;
    }

    // When the user clicks on one the Go back button, this function is triggered
    public function previous($page = null)
    {
        $this->jackpots     = false;
        $this->cashouts     = false;
        $this->showJackpot  = false;
        $this->$page        = true;
        if($page) {
            $this->menu     = false;
        } else {
            $this->menu     = true;
        }
    }

    // When the user clicks on one jackpot on the Jackpots page, that click event triggers this function
    public function showJackpot($name, $id = null)
    {
        $this->jackpots     = false;
        $this->showJackpot  = true;
        $this->jackpotTitle = ucfirst($name);
        if($id == null) {
            //logica pt external
            $this->jackpotValue = DB::connection('mysql_main')->select("call LmiPP.GetExternalJackpotsValues('$name', '10')");
        } else {
            //logica pt internal
            $this->jackpotValue = DB::connection('mysql_main')->select("call LmiPP.GetSystemJackpotsValues('$id', '10')");
        }
    }
}
