<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Question;

class QuestionsController extends Controller
{
    public function create()
    {
        return view('questions/create');
    }

    public function store(Question $question)
    {
        $data = request()->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'image|nullable',
            'tags' => 'nullable',
        ]);

        if (array_key_exists('image', $data) == true) {
            $imagePath = request('image')->store('uploads', 'public');
        }
        else {
            $imagePath = null;
        }

        if ($data['tags'] != null) {
            $data['tags'] = str_replace(" ", "-", $data['tags']);
        }

        auth()->user()->questions()->create([
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $imagePath,
            'tags' => $data['tags'],
        ]);

        return redirect('/question/' . Question::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->first()->id);
    }

    public function show(\App\Models\Question $question)
    {
        $user = auth()->user();
        
        return view('questions.show', compact('question', 'user'));
    }

    public function edit(User $user, Question $question)
    {
        $q = Question::find($question['id']);

        if ($q->user_id !== auth()->user()->id) {
            abort(403);
        }

        if (request()->query('markAsSolved') != null) {
            $this->update($user, $question);

            return redirect("/question/{$question['id']}");
        }

        return view('questions.edit', compact('user', 'question'));
    }

    public function update(User $user, Question $question)
    {
        $q = Question::find($question['id']);

        if ($q->user_id !== auth()->user()->id) {
            abort(403);
        }

        if (request()->query('markAsSolved') != null) {
            $q->solved = request()->query('markAsSolved') == "true" ? 1 : 0;
            $q->save();

            return;
        }
        
        $data = request()->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'image|nullable',
            'tags' => 'nullable',
            'deleteImage' => 'nullable'
        ]);

        if (array_key_exists('deleteImage', $data) == true) {
            $data['image'] = null;

            unset($data['deleteImage']);
        }
        else {
            if (array_key_exists('image', $data) == true) {
                $data['image'] = request('image')->store('uploads', 'public');
            }
            else {
                $data['image'] = $q->image;
            }
        }

        if ($data['tags'] != null) {
            $data['tags'] = str_replace(" ", "-", $data['tags']);
        }

        $q->update($data);

        return redirect("/question/{$question['id']}");
    }

    public function destroy($id)
    {
        $question = Question::find($id);

        $question->delete();

        return redirect()->route('home');
    }
}
