<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResponseController extends Controller
{
    public function store(Request $request, $form_slug)
    {
        $form = Form::where("slug", $form_slug)->first();

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        $email_domain = substr(strchr($request->user()->email, "@"), 1);

        if (!in_array($email_domain, $form->allowed_domains)) {
            return response()->json(['message' => 'Forbidden access'], 403);
        }

        if($form->limit_one_response && Response::where('form_id', $form->id)->where('user_id', $request->user()->id)->exists())
        {
            return response()->json(['message' => `You can't submit form twice`], 422);
        }

        $request->validate([
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.value' => 'required'
        ]);

        if (!is_array($request->answers)) {
            return response()->json(['message' => 'Invalid answers data'], 422);
        }

        foreach ($request->answers as $answer) {
            Response::create([
                'question_id' => $answer['question_id'],
                'value' => $answer['value'],
                'form_id' => $form->id,
                'user_id' => $request->user()->id
            ]);
        }

        return response()->json([
            'message' => 'Submit response success'
        ], 200);
    }
}
