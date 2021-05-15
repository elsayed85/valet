<?php

namespace App\Valet;

use App\Exceptions\SiteNotFoundException;
use App\Models\Site as ModelsSite;
use Site;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class Valet
{
    private $dir;
    private $output;
    public $project_path;
    private $project_name;

    public function __construct($dir = null)
    {
        $this->dir = $dir ?? config('valet.default_dir');
    }

    public function setWorkingDirectory($dir)
    {
        $this->dir = $dir;
    }

    public function runCommand($command)
    {
        $process = new Process(array_merge(['valet'], explode(" ", $command)));
        $process->setWorkingDirectory($this->dir);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->output = $process->getOutput();
        return $this;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function outputAsJson($isArray = false)
    {
        return json_decode($this->getOutput(), $isArray);
    }

    public function outputAsArray($isArray = true)
    {
        return $this->outputAsJson(true);
    }

    public function outputAsCollection()
    {
        return collect($this->outputAsArray());
    }

    public function allSites()
    {
        $this->runCommand('links --json');
        return $this->outputAsCollection()->map(function ($site) {
            return ['name' => $site['site'], 'secured' => !empty($site['secured']), 'url' => $site['url'], 'path' => $site['path']];
        });
    }

    private function setProjectPath($path, $name)
    {
        $this->project_path = $path;
        $this->project_name = $name ?? basename($this->project_path);
        return $this;
    }

    public function link($path, $name = null, $secure = false)
    {
        $this->setProjectPath($path, $name);
        $secure = $secure ? "--secure " : "";
        $this->setWorkingDirectory($this->project_path);
        $this->runCommand("link {$secure}{$this->project_name}");
        return $this;
    }

    public function findSite($project_name)
    {
        return $this->allSites()->where("name", $project_name)->first();
    }

    public function findSiteByPath($path)
    {
        return $this->allSites()->where("path", $path)->first();
    }

    public function save()
    {
        $site = $this->findSite($this->project_name);
        $siteModel = ModelsSite::updateOrCreate(['name' => $this->project_name], $site);
        return $siteModel;
    }

    public function secure($name)
    {
        set_time_limit(0);
        $site = $this->findSite($name);
        if (!$site) {
            throw new SiteNotFoundException("Site Not Found");
        }
        $this->runCommand("secure {$site['name']}");
        return true;
    }

    public function unSecure($name)
    {
        $site = $this->findSite($name);
        if (!$site) {
            throw new SiteNotFoundException("Site Not Found");
        }
        $this->runCommand("unsecure {$site['name']}");
        return true;
    }

    public function unlink($name)
    {
        $site = $this->findSite($name);
        if (!$site) {
            throw new SiteNotFoundException("Site Not Found");
        }

        $this->runCommand("unlink {$site['name']}");
        ModelsSite::whereName($site['name'])->delete();
        return true;
    }

    public function forget($path)
    {
        $site = $this->findSiteByPath($path);

        if (!$site) {
            throw new SiteNotFoundException("Site Not Found");
        }

        $this->setWorkingDirectory($site['path']);
        $this->runCommand("forget");
        return true;
    }

    public function logs()
    {
        $this->runCommand("log --json");
        return $this->outputAsArray();
    }

    public function loadLogFile($path, $take = 10, $skip = 0)
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return collect($lines)->reverse()->skip($skip)->take($take);
    }

    public function onLatestVerision()
    {
        return trim($this->runCommand("on-latest-version")->getOutput()) == "Yes";
    }

    public function run()
    {
        $this->runCommand("--format=json");
        return $this->outputAsArray();
    }
}
