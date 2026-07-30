<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function index()
    {
        return view('admin.subjects.index');
    }

    public function list()
    {
        $subjects = Subject::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $subjects
        ]);
    }

    public function store(Request $request, AuditLoggerService $logger)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:subjects,name',
            'description' => 'required|string',
            'class_range_from' => 'required|integer|min:1|max:8',
            'class_range_to' => 'required|integer|min:1|max:8|gte:class_range_from',
            'session_duration' => 'required|integer|min:10',
            'sort_order' => 'required|integer|min:0',
            'status' => 'required|in:active,disabled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $subject = Subject::create($validator->validated());
        
        $logger->log('Subject', 'Create', "Subject '{$subject->name}' was created.", null, $subject->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Subject created successfully',
            'data' => $subject
        ]);
    }

    public function show(Subject $subject)
    {
        return response()->json([
            'status' => 'success',
            'data' => $subject
        ]);
    }

    public function update(Request $request, Subject $subject, AuditLoggerService $logger)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'description' => 'required|string',
            'class_range_from' => 'required|integer|min:1|max:8',
            'class_range_to' => 'required|integer|min:1|max:8|gte:class_range_from',
            'session_duration' => 'required|integer|min:10',
            'sort_order' => 'required|integer|min:0',
            'status' => 'required|in:active,disabled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldData = $subject->toArray();
        $subject->update($validator->validated());
        
        $logger->log('Subject', 'Update', "Subject '{$subject->name}' was updated.", $oldData, $subject->refresh()->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Subject updated successfully',
            'data' => $subject
        ]);
    }

    public function destroy(Subject $subject, AuditLoggerService $logger)
    {
        $oldData = $subject->toArray();
        $name = $subject->name;
        $subject->delete();
        
        $logger->log('Subject', 'Delete', "Subject '{$name}' was deleted.", $oldData);

        return response()->json([
            'status' => 'success',
            'message' => 'Subject deleted successfully'
        ]);
    }

    /**
     * Change the status of a subject or multiple subjects.
     */
    public function changeStatus(Request $request, AuditLoggerService $logger)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:subjects,id',
            'status' => 'required|in:active,disabled'
        ]);

        Subject::whereIn('id', $request->ids)->update(['status' => $request->status]);
        
        $logger->log('Subject', 'StatusUpdate', "Status of " . count($request->ids) . " subjects changed to {$request->status}.", null, ['ids' => $request->ids, 'status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully for ' . count($request->ids) . ' record(s).'
        ]);
    }

    /**
     * Bulk delete subjects.
     */
    public function bulkDelete(Request $request, AuditLoggerService $logger)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:subjects,id',
        ]);

        $count = count($request->ids);
        Subject::whereIn('id', $request->ids)->delete();

        $logger->log('Subject', 'BulkDelete', "{$count} subjects were deleted.", null, ['ids' => $request->ids]);

        return response()->json([
            'status' => 'success',
            'message' => "{$count} subject(s) deleted successfully."
        ]);
    }
}
