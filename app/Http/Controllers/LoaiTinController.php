<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TheLoai;
use App\LoaiTin;

class LoaiTinController extends Controller
{
    public function getDanhSach()
    {
        $loaitin = LoaiTin::all();
    	return view('admin.loaitin.danhsach',['loaitin'=>$loaitin]);
    }
    public function getThem()
    {
    	return view('admin.loaitin.them');
    }
    public function postThem(Request $request)
    {
        $this->validate($request,
            [
                'Ten' => 'required|min:3|max:100|unique:TheLoai,Ten'
            ],
            [
                'Ten.required'=>'Bạn chưa nhập tên thể loại',
                'Ten.min'=>'Tên thể loại phải có độ dài từ 3 đến 100 ký tự',
                'Ten.max'=>'Tên thể loại phải có độ dài từ 3 đến 100 ký tự',
                'Ten.unique' => 'Tên thể loại đã tồn tại'
            ]);
        $loaitin = new TheLoai;
        $loaitin->Ten = $request->Ten;
        $loaitin->TenKhongDau = changeTitle($request->Ten);
        $loaitin->save();
        return redirect('admin/loaitin/them')->with('thongbao','Thêm thành công');
    }
    public function getSua($id)
    {
        $theloai = TheLoai::all();
        $loaitin = LoaiTin::find($id);
    	return view('admin.loaitin.sua',['loaitin'=>$loaitin,'theloai'=>$theloai]);
    }

    public function postSua(Request $request, $id)
    {
        $this->validate($request,
            [
                'Ten' => 'required|unique:TheLoai,Ten|min:3|max:100'
            ],
            [
                'Ten.required' => 'Bạn chưa nhập tên thể loại',
                'Ten.unique' => 'Tên thể loại đã tồn tại',
                'Ten.min'=>'Tên thể loại phải có độ dài từ 3 đến 100 ký tự',
                'Ten.max'=>'Tên thể loại phải có độ dài từ 3 đến 100 ký tự'
            ]);
        $loaitin = LoaiTin::find($id);
        $loaitin->Ten = $request->Ten;
        $loaitin->TenKhongDau = changeTitle($request->Ten);
        $loaitin->idTheLoai = $request->TheLoai;
        $loaitin->save();
        return redirect('admin/loaitin/sua/'.$id)->with('thongbao','Sửa thành công');
    }

    public function getXoa($id)
    {
        $loaitin = LoaiTin::find($id);
        $loaitin->delete();
        return redirect('admin/loaitin/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
}
