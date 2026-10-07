<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    // ฟังก์ชันให้คะแนนและคอมเมนต์ (Rating & Reviews)
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5', // บังคับคะแนน 1-5 ดาว
            'comment' => 'nullable|string'
        ]);

        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'message' => 'ขอบคุณสำหรับรีวิว!',
            'review' => $review
        ], 201);
    }
}