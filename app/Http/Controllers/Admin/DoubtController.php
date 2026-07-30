<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doubt;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoubtController extends Controller
{
    public function index()
    {
        return view('admin.doubts.index');
    }

    public function list()
    {
        $doubts = Doubt::with(['student', 'subject'])->latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $doubts
        ]);
    }

    public function show($id)
    {
        $doubt = Doubt::with(['student', 'subject'])->findOrFail($id);
        return view('admin.doubts.show', compact('doubt'));
    }

    public function updateStatus(Request $request, $id, AuditLoggerService $logger)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,resolved,cancelled',
        ]);

        $doubt = Doubt::findOrFail($id);
        $oldStatus = $doubt->status;
        $doubt->status = $request->status;
        $doubt->save();

        $logger->log('Doubt', 'StatusUpdate', "Doubt #{$doubt->id} status changed from '{$oldStatus}' to '{$request->status}'.", ['status' => $oldStatus], ['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Doubt status updated successfully'
        ]);
    }

    public function destroy($id, AuditLoggerService $logger)
    {
        $doubt = Doubt::findOrFail($id);
        $oldData = $doubt->toArray();

        if ($doubt->attachment) {
            Storage::disk('public')->delete($doubt->attachment);
        }

        $doubt->delete();

        $logger->log('Doubt', 'Delete', "Doubt #{$id} was deleted.", $oldData);

        return response()->json([
            'status' => 'success',
            'message' => 'Doubt deleted successfully'
        ]);
    }
}
