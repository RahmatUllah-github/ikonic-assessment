<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            // define rules for validation
            $rules = [
                'product_id' => 'required|exists:feedback,id',
                'category_id' => 'required|exists:categories,id',
                'content' => 'required',
            ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            $data['user_id'] = auth()->id(); // get auth user id

            Comment::create($data);
            return ResponseService::successResponse('Commented successfully.');

        } catch (Throwable $th) {
            Log::debug('Store comment error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }

    public function myComments()
    {
        try {
            $authId = auth()->id(); // get auth id
            $pagination_limit = config('data.pagination_limit'); // get pagination limit value from config

            $comments = Comment::where('user_id', $authId)->paginate($pagination_limit);
            return view('my-comments', compact('comments'));

        } catch (Throwable $th) {
            Log::debug('Get my comments error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }

    // only admin is allowed to access this
    public function update(Request $request, Comment $comment)
    {
        try {
            $data = $request->all();

            // define rules for validation
            $rules = [
                'category_id' => 'required|exists:categories,id',
                'content' => 'required',
            ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            $data['user_id'] = auth()->id(); // get auth user id

            $comment->update($data);

            return ResponseService::successResponse('Commented successfully.');
        } catch (Throwable $th) {
            Log::debug('Update comment error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }

    // only admin is allowed to access this
    public function destroy(Comment $comment)
    {
        try {

            $comment->delete();
            return ResponseService::successResponse('Comment deleted successfully.');

        } catch (Throwable $th) {
            Log::debug('Delete comment error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }
}
