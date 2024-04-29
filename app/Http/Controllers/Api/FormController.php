<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
    public function index(Request $request)
    {
        $forms = Form::where('creator_id', $request->user()->id)->get();

        $forms->makeHidden('allowed_domains');

        return response()->json([
            'message' => 'Get all forms success',
            'forms' => $forms
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => [
                'required',
                Rule::unique('forms'),
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            ],
            'allowed_domains' => 'array'
        ]);

        $form = Form::create([
            'name'=> $request->name,
            'slug'=> $request->slug,
            'description' => $request->description,
            'limit_one_response' => $request->limit_one_response ? 1:0,
            'creator_id' => $request->user()->id,
            'allowed_domains' => $request->allowed_domains,
        ]);

        $form->makeHidden('allowed_domains');

        return response()->json([
            'message'=> 'Create Form Success',
            'form' => $form,
        ], 200);
    }

    public function show(Request $request, $slug)
    {
        $form = Form::where('slug', $slug)->with('questions')->firstOrFail();

        if (!$form) {
            return response()->json([
                'message'=> 'Form not Found'
            ], 404);
        }

        $email_domain = substr(strchr($request->user()->email, "@"), 1);

        if(!in_array($email_domain, $form->allowed_domains)) {
            return response()->json([
                'message'=> 'Forbidden access'
            ], 403);
        }

        return response()->json([
            'message'=> 'Get form success',
            'form'=> $form,
        ], 200);
    }
}
