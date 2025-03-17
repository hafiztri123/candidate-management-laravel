<?php

namespace App\Http\Controllers\Api;

use App\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponder;

    public function unread(Request $request)
    {
        return NotificationResource::collection(
            $request->user()->unreadNotifications()->get()
        );
    }

    public function index(Request $request)
    {
        return NotificationResource::collection(
            $request->user()->notifications()->paginate(5)
        );
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return $this->successResponse(null, 'All Notification marked as read', 200);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->unreadNotifications()->where('id', $id)->first();

        if (!$notification) {
            return $this->errorResponse('Notification not found', 404);
        }

        $notification->update(['read_at' => now()]);

        return $this->successResponse(null, 'Notification marked as read', 200);
    }

    public function destroy(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->noContent();
    }
}
