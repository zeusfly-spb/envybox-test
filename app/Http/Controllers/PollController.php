<?php

namespace App\Http\Controllers;

use App\Http\Resources\PollResource;
use App\Http\Resources\VoteResource;
use App\Models\Vote;
use Database\Factories\PollFactory;
use Illuminate\Http\Request;
use App\Models\Poll;

class PollController extends Controller
{
    protected $pollFactory;

    public function __construct()
    {
        $this->pollFactory = new PollFactory();
    }

    /**
     * Summary of store
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required'],
            'options' => ['required', 'array', 'min:2', 'max:4'],
        ]);
        $poll = $this->pollFactory->create($validated['title'], $validated['options']);
        return response()->json(['code' => $poll->short_code]);
    }

    /**
     * Summary of show
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $code)
    {
        // request()->server->set('REMOTE_ADDR', '1.10.15.15');
        $poll = Poll::with('options', 'votes', 'votes.option')->where('short_code', $code)->first();
        return response()->json([
            'poll' => new PollResource($poll),
            'voted' => !!$poll->votes->where('ip_address', request()->ip())->first()
        ]);
    }

    /**
     * Summary of vote
     * @param Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Request $request, string $code)
    {
        // $request->server->set('REMOTE_ADDR', '1.10.15.15');
        $poll = Poll::where('short_code', $code)->first();
        Vote::create([
            'poll_id' => $poll->id,
            'option_id' => $request->input('option_id'),
            'ip_address' => $request->ip(),
        ]);
        $poll->load('votes', 'votes.option');
        return response()->json(VoteResource::collection($poll->votes));
    }
}
