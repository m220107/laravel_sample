<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // Query Parameter から並び変えるカラムを取得
        $order_column = ($request->order_column) ? $request->order_column : 'id';
        // Query Parameter から並び変える種類(ASC/DESC)取得
        $order_value = ($request->order_value) ? $request->order_value : 'asc';
        if ($item_name = $request->item_name) {
            //SELECT * FROM items WHERE name LIKE '%xxxx%' ORDER BY XXX ASC/DESC;
            $items = Item::where('name', 'LIKE', "%{$item_name}%")
                ->orderBy($order_column, $order_value)
                ->get();
        } else {
            //SELECT * FROM items ORDER BY xxxx ASC / DESC;
            $items = Item::orderBy($order_column, $order_value)->get();
        }

        $data = [
            'items' => $items,
            'item_name' => $item_name,
        ];
        // resources/views/item/index.blade.php に受け渡して表示
        return view('item.index', $data);
    }

    public function create()
    {
        // views/item/create.blade.php
        return view('item.create');
    }

    public function store(ItemRequest $request)
    {
        $data = $request->all();
        Item::create($data);
        return redirect(route('item.index'));
    }

    public function show(int $id)
    {
        // $items[1] = "コーヒー";
        // $items[2] = "紅茶";
        // $items[3] = "ほうじ茶";
        $items = [
            1 => 'コーヒー',
            2 => '紅茶',
            3 => 'ほうじ茶',
        ];
        $item = "";
        if ($id > 0 && in_array($id, array_keys($items))) {
            $item = $items[$id];
        }
        // ビューに受け渡すデータ生成
        $data = ['item' => $item];

        // reources/views/item/show.blade.php を表示
        // データを受け渡す
        return view('item.show', $data);
    }

    public function edit(int $id)
    {
        //商品IDから商品データを取得
        // SELECT * FROM items WHERE id = xx;
        $item = Item::find($id);
        $data['item'] = $item;
        //編集画面を表示
        return view('item.edit', $data);
    }

    public function update(ItemRequest $request, int $id)
    {
        $data = $request->all();
        Item::find($id)->fill($data)->save();
        return redirect(route('item.edit', $id));
    }

    public function destroy(int $id)
    {
        // DELETE FROM items WHERE id = xx;
        Item::destroy($id);
        // 一覧画面にリダイレクト
        return redirect(route('item.index'));
    }
}