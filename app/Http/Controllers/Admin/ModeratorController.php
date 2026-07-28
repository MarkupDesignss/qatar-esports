<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ModeratorController extends Controller
{
    public function index()
    {
        $moderators = Admin::where('role', 'moderator')
            ->latest()
            ->paginate(10);

        return view('admin.moderators.index', compact('moderators'));
    }

    public function create()
    {
        return view('admin.moderators.create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|max:255',
    //         'email' => 'required|email|unique:admin,email',
    //         'password' => 'required|min:6|confirmed',
    //     ]);

    //     Admin::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => 'moderator',
    //     ]);

    //     return redirect()->route('admin.moderators.index')
    //         ->with('success', 'Moderator created successfully.');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
        ]);
    
        $plainPassword = $request->password;
    
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'role' => 'moderator',
        ]);
    
      Mail::send([], [], function ($message) use ($admin, $plainPassword) {

        $message->to($admin->email)
            ->subject('Your Moderator Account Details')
            ->html("
                <div style='font-family:Arial,Helvetica,sans-serif;background:#f5f5f5;padding:30px;'>
    
                    <div style='max-width:600px;margin:auto;background:#fff;border:1px solid #ddd;border-radius:8px;padding:30px;'>
    
                        <h2 style='margin-top:0;color:#162E38;'>
                            Welcome, {$admin->name}!
                        </h2>
    
                        <p>Your moderator account has been created successfully.</p>
    
                        <p>Please use the following credentials to log in:</p>
    
                        <table style='width:100%;border-collapse:collapse;margin:20px 0;'>
                            <tr>
                                <td style='padding:10px;border:1px solid #ddd;'><strong>Email</strong></td>
                                <td style='padding:10px;border:1px solid #ddd;'>{$admin->email}</td>
                            </tr>
    
                            <tr>
                                <td style='padding:10px;border:1px solid #ddd;'><strong>Role</strong></td>
                                <td style='padding:10px;border:1px solid #ddd;'>" . ucfirst($admin->role ?? 'Moderator') . "</td>
                            </tr>
    
                            <tr>
                                <td style='padding:10px;border:1px solid #ddd;'><strong>Password</strong></td>
                                <td style='padding:10px;border:1px solid #ddd;'>{$plainPassword}</td>
                            </tr>
                        </table>
    
                        <p style='margin:25px 0;'>
                            <a href='https://www.markupdesigns.net/qatar-esports/admin/login'
                               style='background:#162E38;color:#fff;padding:12px 24px;text-decoration:none;border-radius:5px;display:inline-block;'>
                                Login
                            </a>
                        </p>
    
                        <p>
                            <strong>Important:</strong> After logging in, please change your password immediately.
                        </p>
    
                        <hr style='border:none;border-top:1px solid #eee;margin:25px 0;'>
    
                        <p style='color:#777;font-size:14px;margin-bottom:0;'>
                            Regards,<br>
                            <strong>Qatar Esports Team</strong>
                        </p>
    
                    </div>
    
                </div>
            ");
    });
        return redirect()->route('admin.moderators.index')
            ->with('success', 'Moderator created successfully.');
    }

    public function edit(Admin $moderator)
    {
        return view('admin.moderators.edit', compact('moderator'));
    }

    public function update(Request $request,Admin $moderator)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:admins,email,' . $moderator->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $moderator->update($data);

        return redirect()->route('admin.moderators.index')
            ->with('success', 'Moderator updated successfully.');
    }

    public function destroy(Admin $moderator)
    {
        $moderator->delete();

        return redirect()->route('admin.moderators.index')
            ->with('success', 'Moderator deleted successfully.');
    }
}
