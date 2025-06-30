<?php

namespace App\Http\Controllers\Api\Comments;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    // عرض جميع التعليقات
    public function index()
    {
        $comments = Comment::all();
        return response()->json([
            'status' => 'success',
            'data' => $comments,
        ]);
    }

    // إنشاء تعليق جديد
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'massage' => 'required|string',
        ], [
            'f_name.required' => 'الاسم الأول مطلوب',
            'l_name.required' => 'الاسم الأخير مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'massage.required' => 'حقل الرسالة مطلوب',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'massage' => 'فشل التحقق من البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = Comment::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email'  => $request->email,
            'massage' => $request->massage, // ✅ تم التصحيح هنا
        ]);

        return response()->json([
            'status' => 'success',
            'massage' => 'تم إنشاء التعليق بنجاح',
            'data' => $comment,
        ], 201);
    }

    // عرض تعليق محدد
    public function show(string $comment_id)
    {
        $comment = Comment::find($comment_id);

        if (!$comment) {
            return response()->json([
                'status' => 'error',
                'massage' => 'لم يتم العثور على التعليق',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $comment,
        ]);
    }

    // حذف تعليق
    public function destroy(string $comment_id)
    {
        $comment = Comment::find($comment_id);

        if (!$comment) {
            return response()->json([
                'status' => 'error',
                'massage' => 'التعليق غير موجود',
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'massage' => 'تم حذف التعليق بنجاح',
        ]);
    }
}
