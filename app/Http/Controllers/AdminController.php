<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Event;
use App\Events\BanUser;
use App\Models\Comment;
use App\Models\University;
use App\Models\EventReport;
use Illuminate\Http\Request;
use App\Models\CommentReport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    static $diskName = 'ImagesSaved';

    public function index()
    {
        $usersPage = request('usersPage', 1);
        $eventsPage = request('eventsPage', 1);

        $users = User::where('id', '!=', Auth::id())
            ->where('is_deleted', false)
            ->orderBy('name', 'asc')
            ->paginate(10, ['*'], 'usersPage')
            ->withPath('?eventsPage=' . $eventsPage);
        $events = Event::orderBy('name')->paginate(10, ['*'], 'eventsPage')->withPath('?usersPage=' . $usersPage);

        return view('layouts.admin.dashboard', compact('users', 'events'));
    }


    public function create()
    {
        $universities = University::all();
        return view('layouts.admin.create', compact('universities'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'birthdate' => 'required|date',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone_number' => 'required|unique:users',
            'university_id' => 'required|exists:university,id',
            'image_url' => 'sometimes|image|max:2048', // Validate if the file is an image and its size
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imageUrl = '';
        if ($request->hasFile('image_url') && $request->file('image_url')->isValid()) {
            $imageFile = $request->file('image_url');
            $imageUrl = $this->uploadImage($imageFile);
        }

        $user = User::create([
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'university_id' => $request->university_id,
            'image_url' => $imageUrl,
        ]);

        // Now create the Admin record and associate it with the User
        $admin = new Admin;
        $user->admin()->save($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Admin created successfully!');
    }

    public function reports()
    {
        $events = Event::where('status', '!=', 'BANNED')
            ->whereHas('event_report', fn($query) => $query->where('analyzed', false))
            ->withCount([
                'event_report as event_report_count' => function ($query) {
                    $query->where('analyzed', false);
                }
            ])->get();

        $events = $events->sortByDesc('event_report_count');

        $comments = Comment::where('is_deleted', false)
            ->whereHas('comment_report', fn($query) => $query->where('analyzed', false))
            ->whereHas('user', fn($query) => $query->where('is_deleted', false))
            ->withCount([
                'comment_report as comment_report_count' => function ($query) {
                    $query->where('analyzed', false);
                }
            ])->get();
        $comments = $comments->sortByDesc('comment_report_count');

        return view('layouts.admin.reports', compact('events', 'comments'));

    }

    public function eventReports($eventId, $reason)
    {
        if ($reason == "All") {
            $eventReports = EventReport::where('event_id', $eventId)
                ->where('analyzed', false)
                ->with('user')
                ->get();
        } else {
            $eventReports = EventReport::where('event_id', $eventId)
                ->where('reason', $reason)
                ->where('analyzed', false)
                ->with('user')
                ->get();
        }

        return response()->json($eventReports);
    }

    public function commentReports($userId, $reason)
    {
        $comment = Comment::where('user_id', $userId)->first();

        if ($reason == "All") {
            $commentReports = CommentReport::where('comment_id', $comment->id)
                ->where('analyzed', false)
                ->with('user')
                ->get();
        } else {
            $commentReports = CommentReport::where('comment_id', $comment->id)
                ->where('reason', $reason)
                ->where('analyzed', false)
                ->with('user')
                ->get();
        }

        return response()->json($commentReports);
    }

    public function banUser($userId)
    {
        if (Auth::user()->isAdmin()) {
            $user = User::find($userId);

            if ($user) {
                $user->update(['is_banned' => !$user->is_banned]);
                event(new BanUser($user->id));
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('admin.dashboard');
            }

        } else {
            return back()->withErrors('You don\'t have permission to access this');
        }
    }

    private function uploadImage(UploadedFile $imageFile, $path = 'user'): string
    {
        $fileName = $imageFile->hashName();
        $imageFile->storeAs($path, $fileName, self::$diskName);
        return $fileName;
    }
}
