<?php

namespace App\Http\Livewire;

use App\Models\Site;
use App\Valet\Valet;
use Livewire\Component;
use Livewire\WithPagination;

class Sites extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $project_path;
    public $project_name;
    public $onLatestVerision;
    public $logs = [];
    public $logFileLines;
    private $logFilePath;
    public $logPerNum = 5;
    public $info = [];
    public $valetSites = [];

    public function render()
    {
        return view('livewire.sites', [
            'sites' => Site::paginate(10)
        ]);
    }

    public function toggleSecure($id)
    {
        $site = Site::find($id);
        if ($site) {
            $valet = new Valet();
            $site->secured ? $valet->unSecure($site->name) : $valet->secure($site->name);
            $site->update(['secured' => !$site->secured]);
        }
    }

    public function unlink($id)
    {
        $site = Site::find($id);
        if ($site) {
            $valet = new Valet();
            $valet->unlink($site->name);
        }
    }

    public function link()
    {
        if (empty($this->project_name)) {
            $this->project_name = null;
        }
        (new Valet())->link($this->project_path, $this->project_name)->save();
    }

    public function checkForUpdate()
    {
        $this->onLatestVerision = (new Valet())->onLatestVerision();
    }

    public function sync()
    {
        Site::refreshAll();
    }

    public function clear()
    {
        Site::truncate();
    }

    public function showLogs()
    {
        if ($this->logs) {
            $this->logs = [];
            return;
        }

        $this->logs = (new Valet())->logs();
    }

    public function showLog($path, $skip = 0)
    {
        $this->logFilePath = $path;
        $this->logFileLines = (new Valet())->loadLogFile($this->logFilePath, $this->logPerNum, $skip)->toArray();
    }


    public function run()
    {
        if ($this->info) {
            $this->info = [];
            return;
        }

        $this->info = (new Valet())->run();
    }

    public function showValetsites()
    {
        if ($this->valetSites) {
            $this->valetSites = [];
            return;
        }

        $this->valetSites = (new Valet())->allSites();
    }
}
