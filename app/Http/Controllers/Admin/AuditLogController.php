<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('admin.audit-logs.index');
    }

    public function list(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->has('module') && $request->module !== '') {
            $query->where('module', $request->module);
        }

        if ($request->has('action') && $request->action !== '') {
            $query->where('action', $request->action);
        }

        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        $logs = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $logs
        ]);
    }

    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);
        return view('admin.audit-logs.show', compact('log'));
    }
}
