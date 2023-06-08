<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\News;

class NewsController extends Controller
{
    public function add()
    {
        return view('admin.news.create');
    }

    public function create(Request $request)
    {
        // Validationを行う
        $this->validate($request, News::$rules);

        $news = new News;
        $form = $request->all();

        // フォームから画像が送信されてきたら、保存して、$news->image_path に画像のパスを保存する
        if (isset($form['image'])) {
            $path = $request->file('image')->store('public/image');
            $news->image_path = basename($path);
        } else {
            $news->image_path = null;
        }

        // フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        // フォームから送信されてきたimageを削除する
        unset($form['image']);

        // データベースに保存する
        $news->fill($form);
        $news->save();

        return redirect('admin/news/create');
    }
//     最初に、$request変数から取得したデータに対して、News::$rulesに指定されたバリデーションルールを適用します。バリデーションが失敗した場合、エラーメッセージを表示して、リダイレクトします。

// 次に、新しいNewsモデルのインスタンスを作成し、$request変数から取得したデータを$form変数に代入します。

// $form変数から、'image'キーに関連付けられたファイルが送信された場合、そのファイルを保存し、ファイルのパスを$news->image_pathに保存します。ファイルが送信されなかった場合、$news->image_pathにnullを代入します。

// フォームから送信されてきた_tokenを削除します。

// フォームから送信されてきた'image'キーを削除します。

// $news変数に、$form変数から取得したデータを代入します。

// データベースに保存します。

// 最後に、'admin/news/create'ページにリダイレクトします。これは、ニュース記事を作成するためのフォームを表示するページです。
    
    

    public function index(Request $request)
    {
        $cond_title = $request->cond_title;
        if ($cond_title != '') {
            // 検索されたら検索結果を取得する
            $posts = News::where('title', $cond_title)->get();
        } else {
            // それ以外はすべてのニュースを取得する
            $posts = News::all();
        }
        return view('admin.news.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }
    
//     最初に、$cond_title変数に、HTTPリクエストから送信された検索条件が代入されます。

// $cond_title変数の値が空でない場合、where()メソッドを使用して、指定された条件に一致するレコードをデータベースから取得します。この場合、'title'カラムが$cond_title変数の値と一致するレコードが取得されます。

// $cond_title変数の値が空の場合、all()メソッドを使用して、データベース内のすべてのレコードを取得します。

// 最後に、ビューに取得したレコードを渡します。この場合、'record.index'というビューに、$posts変数と$cond_title変数を配列形式で渡しています。このビューは、レコードを表示するためのHTMLコードを含んでいます。

// この関数は、Webアプリケーションの一部として使用されることがあります。ユーザーがフォームに入力した検索条件に基づいて、データベースからレコードを取得し、それを表示するために使用されます。
    

    // 以下を追記

    public function edit(Request $request)
    {
        // News Modelからデータを取得する
        $news = News::find($request->id);
        if (empty($news)) {
            abort(404);
        }
        return view('admin.news.edit', ['news_form' => $news]);
    }
    // 最初に、$request変数から取得したidを使用して、Newsモデルから指定されたIDのデータを取得します。
    // $news変数が空である場合、HTTPステータスコード404を返して、エラーページを表示します。
    // フォームの表示に必要なデータをビューに渡します。この場合、ニュース記事を編集するためのフォームを表示するビューの名前は'admin.news.edit'であり、
    // ビューに渡されるデータは、'news_form'というキーに$news変数が関連付けられた配列です。

public function update(Request $request)
    {
        // Validationをかける
        $this->validate($request, News::$rules);
        // News Modelからデータを取得する
        $news = News::find($request->id);
        // 送信されてきたフォームデータを格納する
        $news_form = $request->all();

        if ($request->remove == 'true') {
            $news_form['image_path'] = null;
        } elseif ($request->file('image')) {
            $path = $request->file('image')->store('public/image');
            $news_form['image_path'] = basename($path);
        } else {
            $news_form['image_path'] = $news->image_path;
        }

        unset($news_form['image']);
        unset($news_form['remove']);
        unset($news_form['_token']);

        // 該当するデータを上書きして保存する
        $news->fill($news_form)->save();

        return redirect('admin/news');
//     }まず、送信されたデータが指定されたバリデーションルールに従っているかをチェックします。

// 次に、編集対象となるニュース記事のデータを取得します。

// その後、送信されたフォームデータから、画像がアップロードされたかどうかを判断し、アップロードされた場合にはサーバーに保存し、その保存したパスをデータベースに保存します。

// また、削除フラグがセットされている場合には、画像パスをnullに設定します。

// 最後に、必要のないフォームデータを削除して、上書きして保存します。

// 最後に、ニュース記事一覧画面にリダイレクトします。

    // 以下を追記

    public function delete(Request $request)
    {
        // 該当するNews Modelを取得
        $news = News::find($request->id);

        // 削除する
        $news->delete();

        return redirect('admin/news/');
    }
}
