<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SlotController extends Controller
{
    public function index()
    {
        return view('admin.calendar.index');
    }

    public function list()
    {
        $slots = Slot::orderBy('date', 'desc')->orderBy('start_time', 'asc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $slots
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'status' => 'required|in:available,booked,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $slot = Slot::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Slot created successfully',
            'data' => $slot
        ]);
    }

    public function show($id)
    {
        $slot = Slot::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $slot
        ]);
    }

    public function update(Request $request, $id)
    {
        $slot = Slot::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'status' => 'required|in:available,booked,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $slot->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Slot updated successfully',
            'data' => $slot
        ]);
    }

    public function destroy($id)
    {
        $slot = Slot::findOrFail($id);
        if ($slot->status === 'booked') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete a booked slot.'
            ], 422);
        }

        $slot->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Slot deleted successfully'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:slots,id'
        ]);

        $slots = Slot::whereIn('id', $request->ids)->get();
        foreach ($slots as $slot) {
            if ($slot->status === 'booked') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete booked slots.'
                ], 422);
            }
        }

        Slot::whereIn('id', $request->ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Selected slots deleted successfully.'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:slots,id',
            'status' => 'required|in:available,inactive'
        ]);

        // Don't allow changing status of booked slots to inactive here without checking appointments
        Slot::whereIn('id', $request->ids)
            ->where('status', '!=', 'booked')
            ->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully for available/inactive records.'
        ]);
    }
}
