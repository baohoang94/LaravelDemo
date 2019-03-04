<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TheLoai;
use App\Slide;
use App\LoaiTin;
use App\TinTuc;
use App\User;

use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
	function __construct()
	{
		$theloai = TheLoai::all();
		$slide = Slide::all();
		view()->share('theloai',$theloai);
		view()->share('slide',$slide);
	}
    public function trangchu()
    {
    	return view('pages.trangchu');
    }
    public function lienhe()
    {
        return view('pages.lienhe');
    }
    public function loaitin($id)
    {
        $loaitin = LoaiTin::find($id);
        $tintuc = TinTuc::where('idLoaiTin',$id)->paginate(5);
    	return view('pages.loaitin',['loaitin'=>$loaitin, 'tintuc'=>$tintuc]);
    }

    public function tintuc($id)
    {
        $tintuc = TinTuc::find($id);
        $tinnoibat = TinTuc::where('NoiBat',1)->take(4)->get();
        $tinlienquan = TinTuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();


        return view('pages.tintuc', ['tintuc'=>$tintuc, 'tinnoibat'=>$tinnoibat,'tinlienquan'=>$tinlienquan]);
    }

    public function getDangnhap()
    {
        return view('pages.dangnhap');
    }
    public function postDangnhap(Request $request)
    {
        $this->validate($request, [
            'email'=>'required',
            'password'=>'required|min:3|max:32'
        ],[
            'email.required'=>'Bạn chưa nhập email',
            'password.required'=>'Bạn chưa nhập password',
            'password.min'=>'Password không đc nhỏ hơn 3 ký tự',
            'password.max'=>'Password không đc lớn hơn 32 ký tự'
        ]);

        if (Auth::attempt(['email'=>$request->email, 'password'=>$request->password])) {
            return redirect('trangchu');
        } else {
            return redirect('dangnhap')->with('thongbao','Đăng nhập ko thành công');
        }
    }

    public function getDangxuat($value='')
    {
        Auth::logout();
        return redirect('trangchu');
    }

    public function getNguoidung()
    {
        $user = Auth::user();
        return view('pages.nguoidung',['nguoidung'=>$user]);
    }

    public function postNguoidung(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|min:3',
        ],[
            'name.required'=>'Bạn chưa nhập tên người dùng',
            'name.min'=>'Tên người dùng phải có tối thiểu 3 ký tự'
        ]);

        $user = Auth::user();
        $user->name = $request->name;

        if ($request->changePassword == 'on') {
            $this->validate($request,[
            'password'=>'required|min:3|max:32',
            'passwordAgain'=>'required|same:password'
        ],[
            'password.required'=>'Bạn chưa nhập mật khẩu',
            'password.min'=>'Mật khẩu phải có ít nhất 3 ký tự',
            'password.max'=>'Mật khẩu chỉ đc tối đa 32 ký tự',
            'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu',
            'passwordAgain.same'=>'Bạn nhập lại mật khẩu chưa khớp'
        ]);
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        if ($request->changePassword == 'on') {
            return redirect('dangxuat');
        }
        return redirect('nguoidung')->with('thongbao','Sửa thành công');
    }

    public function getDangky()
    {
        return view('pages.dangky');
    }

    public function postDangky(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:3|max:32',
            'passwordAgain'=>'required|same:password'
        ],[
            'name.required'=>'Bạn chưa nhập tên người dùng',
            'name.min'=>'Tên người dùng phải có tối thiểu 3 ký tự',
            'email.required'=>'Bạn chưa nhập email',
            'email.unique'=>'Email đã tồn tại',
            'password.required'=>'Bạn chưa nhập mật khẩu',
            'password.min'=>'Mật khẩu phải có ít nhất 3 ký tự',
            'password.max'=>'Mật khẩu chỉ đc tối đa 32 ký tự',
            'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu',
            'passwordAgain.same'=>'Bạn nhập lại mật khẩu chưa khớp'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->quyen = 0;
        $user->save();
        return redirect('dangky')->with('thongbao','Đăng ký thành công');
    }

    public function timkiem(Request $request)
    {
        $tukhoa = $request->get('tukhoa');
        $tintuc = TinTuc::where('TieuDe','like',"%$tukhoa%")->orwhere('TomTat','like',"%$tukhoa")->orwhere('NoiDung','like',"%$tukhoa")->take(30)->paginate(5)->appends(['tukhoa' => $tukhoa]);
        return view('pages.timkiem',['tintuc'=>$tintuc,'tukhoa'=>$tukhoa]);
    }
}
