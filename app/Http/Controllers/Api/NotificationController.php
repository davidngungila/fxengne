<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get all notifications
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 50);
        $unreadOnly = $request->input('unread_only', false);

        $query = Notification::orderBy('created_at', 'desc');

        if ($unreadOnly) {
            $query->where('read', false);
        }

        $notifications = $query->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => Notification::where('read', false)->count()
        ]);
    }

    /**
     * Get unread count
     */
    public function unreadCount(): JsonResponse
    {
        $count = Notification::where('read', false)->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id): JsonResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead(): JsonResponse
    {
        Notification::where('read', false)->update([
            'read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy($id): JsonResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }
}
