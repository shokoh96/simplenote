<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Memo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ログインしているユーザー情報をVuewに渡す
        $user = \Auth::user();
        // メモ一覧を取得
        // ASC=昇順、DESC=降順
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        return view('home', compact('user', 'memos'));
    }

    public function create()
    {
        // ログインしているユーザー情報をViewに渡す
        $user = \Auth::user();
        return view('create', compact('user'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // POSTされたデータをDB(memosテーブル)に挿入
        // MEMOモデルにDBへ保存する命令を出す
        $memo_id = Memo::insertGetId([
            'content' => $data['content'],
            'user_id' => $data['user_id'],
            'status' => 1,
        ]);

        // リダイレクト処理
        return redirect()->route('home');
    }
}