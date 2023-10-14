<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProfileController extends Controller
{
    public function index()
    {
        try {
            $authId = auth()->id();

            $user = User::where('id', $authId)->with('role', 'image', 'likes', 'unlikes', 'feedbacks', 'comments')->first();
            return view('profile.profile', compact('user'));

        } catch (Throwable $th) {
            Log::debug('Get profile data error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $authId = auth()->id();

            // define rules for validation
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email,' . $authId,
            ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            $user = User::where('id', $authId)->update($data);
            $user->load('role', 'image', 'likes', 'unlikes', 'feedbacks', 'comments');

            return ResponseService::successResponse('Profile updated successfully.');

        } catch (Throwable $th) {
            Log::debug('Update profile error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }
}
