<div>
    <button type="button" class="btn btn-primary mb-2" wire:click="checkForUpdate">check for update</button>
    <button type="button" class="btn btn-primary mb-2" wire:click="sync">Sync</button>
    <button type="button" class="btn btn-primary mb-2" wire:click="clear">Clear</button>
    <button type="button" class="btn btn-primary mb-2" wire:click="showLogs">logs</button>
    <button type="button" class="btn btn-primary mb-2" wire:click="run">run</button>
    <button type="button" class="btn btn-primary mb-2" wire:click="showValetsites">valet sites</button>

    @if($onLatestVerision === false)
    <div class="alert alert-primary" role="alert">
        Please Update Laravel Valet . <a href="https://github.com/cretueusebiu/valet-windows" target="_blank"
            rel="noopener noreferrer">click here</a>
    </div>
    @elseif($onLatestVerision === true)
    <div class="alert alert-primary" role="alert">
        You are in the latest verision
    </div>
    @endif

    <form class="form-inline" wire:submit.prevent="link">
        <div class="form-group mb-2">
            <input type="text" class="form-control" placeholder="project path" wire:model="project_path" required>
        </div>
        <div class="form-group mb-2">
            <input type="text" class="form-control" placeholder="project name" wire:model="project_name">
        </div>
        <button type="submit" class="btn btn-primary mb-2">Link</button>
    </form>

    @if($logs && $logFileLines)
    <table class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>--</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logFileLines as $line)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $line }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($logs)
    <table class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>File path</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $log[0] }}</td>
                <td><button class="btn" wire:click="showLog('{{ $log[1] }}')">{{ $log[1] }}</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($info)
    <table class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Usage</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($info['commands'] as $command)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $command['name'] }}</td>
                <td>{{ $command['description'] }}</td>
                <td>{{ $command['usage'][0] ?? "" }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($valetSites)
    <table class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Secured</th>
                <th>Url</th>
                <th>Path</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($valetSites as $vsite)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $vsite['name'] }}</td>
                <td>{{ ($vsite['secured'] ? "True" : "False" )}}</td>
                <td><a href="{{ $vsite['url'] }}" target="_blank" rel="noopener noreferrer">{{ $vsite['url'] }}</a></td>
                <td><a href="{!! $vsite['path'] !!}" target="_blank" rel="noopener noreferrer">{{ $vsite['path'] }}</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <table class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Secured</th>
                <th>Url</th>
                <th>Path</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sites as $site)
            <tr>
                <td>{{ $site->id }}</td>
                <td>{{ $site->name }}</td>
                <td>{{ ($site->secured ? "True" : "False" )}}</td>
                <td><a href="{{ $site->url }}" target="_blank" rel="noopener noreferrer">{{ $site->url }}</a></td>
                <td><a href="{!! $site->path !!}" target="_blank" rel="noopener noreferrer">{{ $site->path }}</a></td>
                <td>
                    <button class="btn btn-danger" wire:click="unlink({{ $site->id }})">unlink</button>
                    {{-- <button  wire:click="toggleSecure({{ $site->id }})" class="btn @if($site->secured) btn-danger
                    @else btn-success @endif">@if($site->secured) unsecure @else secure @endif</button> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $sites->render() }}
</div>
