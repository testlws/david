<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinkResource;
use Illuminate\Support\Facades\Auth;
use App\Link;
use App\User;

class LinkController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $links = Link::orderBy('title')->with('category')->get();
        foreach ($links as $link) {
            $reactantFacade = $link->viaLoveReactant();
            $link->liked = $reactantFacade->isReactedBy($user, 'Like');
            $link->disliked = $reactantFacade->isReactedBy($user, 'Dislike');
            $link->likes = $reactantFacade->getReactionCounterOfType('Like')->getCount();
            $link->dislikes = $reactantFacade->getReactionCounterOfType('Dislike')->getCount();
            $link->votes = $link->likes + $link->dislikes;
            $link->score = '0.00';
            if ($link->votes > 0) {
                $link->score = round(5 * ($link->likes / $link->votes), 2);
                $link->score = number_format($link->score, 2, '.', ',');
            }
        }

        $coll = LinkResource::collection($links);
        return $coll;
    }

    public function byCategory($category_id)
    {
        $user = auth()->user();

        $links = Link::where('category_id', $category_id)->with('category')->get();
        foreach ($links as $link) {
            $reactantFacade = $link->viaLoveReactant();
            $link->liked = $reactantFacade->isReactedBy($user, 'Like');
            $link->disliked = $reactantFacade->isReactedBy($user, 'Dislike');
            $link->likes = $reactantFacade->getReactionCounterOfType('Like')->getCount();
            $link->dislikes = $reactantFacade->getReactionCounterOfType('Dislike')->getCount();
            $link->votes = $link->likes + $link->dislikes;
            $link->score = '0.00';
            if ($link->votes > 0) {
                $link->score = round(5 * ($link->likes / $link->votes), 2);
                $link->score = number_format($link->score, 2, '.', ',');
            }
        }

        $coll = LinkResource::collection($links);
        return $coll;
    }

    public function like($link_id)
    {
        $link = Link::find($link_id);
        $user = auth()->user();
        
        if (!$link) return response()->json(['error' => 'link_does_not_exists'], 404);
        if (!$user) return response()->json(['error' => 'must_login'], 401);

        $reacterFacade = $user->viaLoveReacter();
        $reactantFacade = $link->viaLoveReactant();

        $hasNotLiked = $reacterFacade->hasNotReactedTo($link, 'Like');
        if ($hasNotLiked) { 
            $reacterFacade->reactTo($link, 'Like');
            if ($reacterFacade->hasReactedTo($link, 'Dislike')) $reacterFacade->unreactTo($link, 'Dislike');
    
            $likes = $reactantFacade->getReactionCounterOfType('Like')->getCount();
            $dislikes = $reactantFacade->getReactionCounterOfType('Dislike')->getCount();

            $votes = $likes + $dislikes;
            $score = '0.00';
            if ($votes > 0) {
                $score = round(5 * ($likes / $votes), 2);
                $score = number_format($score, 2, '.', ',');
            }

            return response()->json(['status' => 'link_liked', 'likes' => $likes, 'dislikes' => $dislikes, 'votes' => $votes, 'score' => $score], 200);
        } else {
            return response()->json(['error' => 'link_is_already_liked', 'likes' => $likes, 'dislikes' => $dislikes, 'votes' => $votes, 'score' => $score], 200);
        }
    }

    public function unlike($link_id)
    {
        $link = Link::find($link_id);
        $user = auth()->user();
        
        if (!$link) return response()->json(['error' => 'link_does_not_exists'], 404);
        if (!$user) return response()->json(['error' => 'must_login'], 401);

        $reacterFacade = $user->viaLoveReacter();
        $reactantFacade = $link->viaLoveReactant();

        $hasLiked = $reacterFacade->hasReactedTo($link, 'Like');
        if ($hasLiked) { 
            $reacterFacade->unreactTo($link, 'Like');
            $likes = $reactantFacade->getReactionCounterOfType('Like')->getCount();
            $dislikes = $reactantFacade->getReactionCounterOfType('Dislike')->getCount();
            $votes = $likes + $dislikes;
            $score = '0.00';
            if ($votes > 0) {
                $score = round(5 * ($likes / $votes), 2);
                $score = number_format($score, 2, '.', ',');
            }

            return response()->json(['status' => 'link_unliked', 'likes' => $likes, 'dislikes' => $dislikes, 'votes' => $votes, 'score' => $score], 200);
        } else {
            $likes = $reactantFacade->getReactionCounterOfType('Like')->getCount();
            $dislikes = $reactantFacade->getReactionCounterOfType('Dislike')->getCount();
            $votes = $likes + $dislikes;
            $score = '0.00';
            if ($votes > 0) {
                $score = round(5 * ($likes / $votes), 2);
                $score = number_format($score, 2, '.', ',');
            }

            return response()->json(['error' => 'link_is_not_liked', 'likes' => $likes, 'dislikes' => $dislikes, 'votes' => $votes, 'score' => $score], 200);
        }
    }

    public function dislike($link_id)
    {
        $link = Link::find($link_id);
        $user = auth()->user();
        
        if (!$link) return response()->json(['error' => 'link_does_not_exists'], 404);
        if (!$user) return response()->json(['error' => 'must_login'], 401);

        $reacterFacade = $user->viaLoveReacter();
        $reactantFacade = $link->viaLoveReactant();

        $hasNotDisliked = $reacterFacade->hasNotReactedTo($link, 'Dislike');
        if ($hasNotDisliked) { 
            $reacterFacade->reactTo($link, 'Dislike');
            if ($reacterFacade->hasReactedTo($link, 'Like')) $reacterFacade->unreactTo($link, 'Like');
            $likes = $reactantFacade->getReactionCounterOfType('Like')->getCount();
            $dislikes = $reactantFacade->getReactionCounterOfType('Dislike')->getCount();
            $votes = $likes + $dislikes;
            $score = '0.00';
            if ($votes > 0) {
                $score = round(5 * ($likes / $votes), 2);
                $score = number_format($score, 2, '.', ',');
            }

            return response()->json(['status' => 'link_disliked', 'likes' => $likes, 'dislikes' => $dislikes, 'votes' => $votes, 'score' => $score], 200);
        } else {
            $likes = $reactantFacade->getReactionCounterOfType('Like')->getCount();
            $dislikes = $reactantFacade->getReactionCounterOfType('Dislike')->getCount();
            $votes = $likes + $dislikes;
            $score = '0.00';
            if ($votes > 0) {
                $score = round(5 * ($likes / $votes), 2);
                $score = number_format($score, 2, '.', ',');
            }
            return response()->json(['error' => 'link_is_already_disliked', 'likes' => $likes, 'dislikes' => $dislikes, 'votes' => $votes, 'score' => $score], 200);
        }
    }

    public function undislike($link_id)
    {
        $link = Link::find($link_id);
        $user = auth()->user();
        
        if (!$link) return response()->json(['error' => 'link_does_not_exists'], 404);
        if (!$user) return response()->json(['error' => 'must_login'], 401);

        $reacterFacade = $user->viaLoveReacter();
        $reactantFacade = $link->viaLoveReactant();

        $hasDisliked = $reacterFacade->hasReactedTo($link, 'Dislike');
        if ($hasDisliked) { 
            $reacterFacade->unreactTo($link, 'Dislike');
            $likes = $reactantFacade->getReactionCounterOfType('Like')->getCount();
            $dislikes = $reactantFacade->getReactionCounterOfType('Dislike')->getCount();
            $votes = $likes + $dislikes;
            $score = '0.00';
            if ($votes > 0) {
                $score = round(5 * ($likes / $votes), 2);
                $score = number_format($score, 2, '.', ',');
            }

            return response()->json(['status' => 'link_undisliked', 'likes' => $likes, 'dislikes' => $dislikes, 'votes' => $votes, 'score' => $score], 200);
        } else {
            $likes = $reactantFacade->getReactionCounterOfType('Like')->getCount();
            $dislikes = $reactantFacade->getReactionCounterOfType('Dislike')->getCount();
            $votes = $likes + $dislikes;
            $score = '0.00';
            if ($votes > 0) {
                $score = round(5 * ($likes / $votes), 2);
                $score = number_format($score, 2, '.', ',');
            }
            return response()->json(['error' => 'link_is_not_disliked', 'likes' => $likes, 'dislikes' => $dislikes, 'votes' => $votes, 'score' => $score], 200);
        }
    }
}