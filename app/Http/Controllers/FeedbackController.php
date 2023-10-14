<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Vote;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Throwable;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            // define rules for validation
            $rules = [
                'product_id' => 'required|exists:products,id',
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|max:255',
                'description' => 'required',
                'attachments' => 'sometimes|array',
                'attachments.*' => 'sometimes|file|max:2048', // max attachment size 2 MB
            ];

            // custom messages
            $messages = [
                'attachments.*' => 'Maximum attachment size is 2 MB.',
            ];

            $validator = Validator::make($data, $rules, $messages);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            $data['user_id'] = auth()->id(); // get auth user id

            $feedback = Feedback::create($data);

            if (isset($data['attachments']) && count($data['attachments']) && $feedback) {

                foreach ($data['attachments'] as $attachment) {
                    $feedback->attachments()->create([
                        'url' => $attachment->store('feedback/attachments', 'public'),
                    ]);
                }
            }

            return ResponseService::successResponse('Feedback created successfully.');
        } catch (Throwable $th) {
            Log::debug('Store feedback error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }

    public function voteFeedback(Request $request)
    {
        try {
            $data = $request->all();

            // define rules for validation
            $rules = [
                'feedback_id' => 'required|exists:feedback,id',
                'vote' => 'required|in:like,unlike',
            ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            $data['user_id'] = auth()->id(); // get auth user id

            if (Vote::where(['user_id' => $data['user_id'], 'feedback_id' => $data['feedback_id']])->exists()) {
                return ResponseService::validationErrorResponse('You have already voted for this feedback.');
            }

            $relation = $data['vote'] . 's'; // identify the relation according to vote

            $feedback = Feedback::find($data['feedback_id']);
            $feedback->$relation->create($data);

            return ResponseService::successResponse('Feedback voted successfully.');
        } catch (Throwable $th) {
            Log::debug('Vote feedback error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }

    public function myFeedbacks()
    {
        try {
            $authId = auth()->id(); // get auth id
            $pagination_limit = config('data.pagination_limit'); // get pagination limit value from config

            $feedbacks = Feedback::where('user_id', $authId)
                ->with([
                    'attachments',
                    'comments',
                    'category',
                    'product:id,name', // retrieve only id and name
                ])
                ->withCount('likes', 'unlikes')
                ->paginate($pagination_limit);

            return view('my-feedbacks', compact('feedbacks'));
        } catch (Throwable $th) {
            Log::debug('Get my feedbacks error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }

    // only admin is allowed to access this
    public function update(Request $request, Feedback $feedback)
    {
        try {
            $data = $request->all();

            // define rules for validation
            $rules = [
                'product_id' => 'required|exists:products,id',
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|max:255',
                'description' => 'required',
                'attachments' => 'sometimes|array',
                'attachments.*' => 'sometimes|file|max:2048', // max attachment size 2 MB
            ];

            // custom messages
            $messages = [
                'attachments.*' => 'Maximum attachment size is 2 MB.',
            ];

            $validator = Validator::make($data, $rules, $messages);

            if ($validator->fails()) {
                return ResponseService::validationErrorResponse($validator->errors()->first());
            }

            $updated = $feedback->update($data);

            if (isset($data['attachments']) && count($data['attachments']) && $updated) {

                // delete the previous attachments
                foreach ($feedback->attachments ?? [] as $att) {
                    File::delete(public_path('storage/' . $att->url));
                }

                foreach ($data['attachments'] as $attachment) {
                    $feedback->attachments()->create([
                        'url' => $attachment->store('feedback/attachments', 'public'),
                    ]);
                }
            }

            return ResponseService::successResponse('Feedback updated successfully.');
        } catch (Throwable $th) {
            Log::debug('Update feedback error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }

    // only admin is allowed to access this
    public function destroy(Feedback $feedback)
    {
        try {
            // delete the previous attachments
            foreach ($feedback->attachments ?? [] as $attachment) {
                File::delete(public_path('storage/' . $attachment->url));
            }

            $feedback->comments()->delete(); // delete comments of the feedback

            $feedback->delete();

            return ResponseService::successResponse('Feedback deleted successfully.');
        } catch (Throwable $th) {
            Log::debug('Delete feedback error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }
}
