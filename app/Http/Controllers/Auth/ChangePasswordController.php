<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Where to redirect users after password is changed.
     *
     * @var string
     */
    protected $redirectTo = '/change_password';

    /**
     * Show the form for changing the password.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        $user = Auth::user();

        return view('auth.change_password', compact('user'));
    }

    /**
     * Handle the password change request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user(); // Use Auth::user() instead of Auth::getUser()

        // Validate the request data
        $this->validator($request->all())->validate();

        // Check if the current password is correct
        if (Hash::check($request->get('current_password'), $user->password)) {
            // Hash the new password before saving
            $user->password = Hash::make($request->get('new_password'));
            $user->save();

            return redirect($this->redirectTo)->with('success', 'Password changed successfully!');
        } else {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
    }

    /**
     * Get a validator for an incoming password change request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
    }
}
