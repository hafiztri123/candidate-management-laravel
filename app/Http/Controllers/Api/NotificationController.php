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
            $request->user()->notifications()->paginate(15)
        );
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return $this->successResponse(null, 'Notification marked as read', 200);
    }

    public function destroy(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->noContent();
    }
}
