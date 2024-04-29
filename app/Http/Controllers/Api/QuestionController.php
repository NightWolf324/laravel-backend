<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function store(Request $request, $form_slug)
    {
        $form = Form::where("slug", $form_slug)->first();

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        $request->validate([
            'name' => 'required',
            'choice_type' => 'required|in:short answer,paragraph,date,multiple choice,dropdown,checkboxes',
            'choices' => 'required_if:choice_type,multiple choice,dropdown,checkboxes|array',
            'is_required' => 'required|boolean'
        ]);

        $question = Question::create([
            'name'=> $request->name,
            'choice_type' => $request->choice_type,
            'is_required' => $request->is_required,
            'choices' => implode(',',$request->choices),
            'form_id' => $form->id
        ]);

        return response()->json([
            'message'=> 'Add question success',
            'question' => $question
        ],200);
    }

    public function destroy($form_slug, $question_id)
    {
        $form = Form::where('slug', $form_slug)->first();

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        $question = Question::find($question_id);

        if(!$question) {
            return response()->json(['message'=> 'Question not found'], 404);
        }

        if($question->form_id != $form->id) {
            return response()->json(['message' => 'Forbidden access'], 403);
        }

        $question->delete();

        return response()->json([
            'message'=> 'Remove question success'
        ],200);

    }
}
