<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Comment;
use App\TinTuc;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function getXoa($id,$idTinTuc)
    {
        $comment = Comment::find($id);
        $comment->delete();
        return redirect('admin/tintuc/sua/'.$idTinTuc)->with('thongbao','Bạn đã xóa comment thành công');
    }

    public function postComment($id,Request $request)
    {
    	$idTinTuc = $id;
    	$tintuc = TinTuc::find($id);
    	$comment = new Comment;
    	$comment->idTinTuc = $idTinTuc;
    	$comment->idUser = Auth::user()->id;
    	$comment->NoiDung = $request->NoiDung;
    	$comment->save();

    	return redirect("tintuc/$id/".$tintuc->TieuDeKhongDau.'.html')->with('thongbao','Viết bình luận thành công');
    }
}
