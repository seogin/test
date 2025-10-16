<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Auth Logs</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f4f4f4; text-align: left; }
        pre { white-space: pre-wrap; word-wrap: break-word; font-size: 12px; }
        select { padding: 5px; margin-bottom: 15px; }
        h1 { margin-bottom: 15px; }
    </style>
</head>
<body>

<h1>Admin Auth Logs</h1>
<div style="margin-bottom: 40px;">
    <a href="{{ route('admin.dashboard') }}" 
    style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none;">
        Back to Dashboard
    </a>
</div>
<form method="get">
    <label for="filter">Filter:</label>
    <select name="filter" id="filter" onchange="this.form.submit()">
        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All</option>
        <option value="login" {{ $filter === 'login' ? 'selected' : '' }}>Login</option>
        <option value="logout" {{ $filter === 'logout' ? 'selected' : '' }}>Logout</option>
        <option value="failed" {{ $filter === 'failed' ? 'selected' : '' }}>Failed Attempts</option>
        <option value="lockout" {{ $filter === 'lockout' ? 'selected' : '' }}>Lockouts</option>
    </select>
</form>

<table>
    <thead>
        <tr>
            <th>Timestamp</th>
            <th>Admin Email</th>
            <th>Action</th>
            <th>IP Address</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->created_at }}</td>
            <td>{{ $log->actorAdmin?->email ?? 'Unknown' }}</td>
            <td>{{ $log->action }}</td>
            <td>{{ $log->ip }}</td>
            <td>
                @if($log->data)
                    <pre>{{ json_encode($log->data, JSON_PRETTY_PRINT) }}</pre>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top: 15px; font-size: 12px; display: flex; gap: 5px; flex-wrap: wrap;">
    @foreach ($logs->links()->elements[0] as $page => $url)
        @if ($page == $logs->currentPage())
            <span style="padding: 2px 6px; border: 1px solid #ccc; border-radius: 3px; background-color: #ddd;">
                {{ $page }}
            </span>
        @else
            <a href="{{ $url }}" style="padding: 2px 6px; border: 1px solid #ccc; border-radius: 3px; text-decoration: none;">
                {{ $page }}
            </a>
        @endif
    @endforeach
</div>


</body>
</html>
