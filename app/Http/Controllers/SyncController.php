<?php

namespace App\Http\Controllers;

use App\Services\BatikSyncService;

class SyncController extends Controller
{
    public function index(BatikSyncService $syncService)
    {
        $stats = $syncService->getStatistics();

        return view('pages.admin.sync', compact('stats'));
    }

    public function sync(BatikSyncService $syncService)
    {
        $result = $syncService->sync();

        return redirect()
            ->route('admin.sync')
            ->with('sync_result', $result);
    }
}