<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;



class ProductController extends Controller
{
    public function index(){
        $products = Product::with('category')->paginate(20);
        // dd($products);
        return response()->json($products);
    }

    public function search(Request $request){
        $search = $request->search;
        $available = $request->available;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $category = $request->category;

        if(!is_null($search) && !is_null($available) && !is_null($min_price) && !is_null($max_price) && !is_null($category)){
            $products = Product::where('name','like', '%'."$search" .'%')
            ->where('available',$available)
            ->whereBetween('price', [$min_price, $max_price])
            ->whereHas('category',function($query) use ($category){
                $query->where('name','like', '%'."$category" .'%');
            })
            ->with('category')
            ->get();
        }elseif(!is_null($search) || !is_null($available) || !is_null($min_price) || !is_null($max_price) || !is_null($category)){
            if(isset($search)){
                if(isset($available)){
                    $products = Product::where('name','like', '%'."$search" .'%')
                    ->where('available',$available)
                    ->with('category')
                    ->get();
                }elseif(isset($min_price) || isset($max_price)){
                    $products = Product::where('name','like', '%'."$search" .'%')
                    ->whereBetween('price', [$min_price, $max_price])
                    ->with('category')
                    ->get();
                }else{
                    $products = Product::where('name','like', '%'."$search" .'%')
                    ->with('category')
                    ->get();
                }
            }elseif(isset($category)){
                if(isset($search)){
                    $products = Product::where('name','like', '%'."$search" .'%')
                    ->whereHas('category',function($query) use ($category){
                        $query->where('name','like', '%'."$category" .'%');
                    })
                    ->with('category')
                    ->get();
                }else{
                    $products = Product::whereHas('category',function($query) use ($category){
                        $query->where('name','like', '%'."$category" .'%');
                    })
                    ->with('category')
                    ->get();
                }
            }elseif(isset($available)){
                if(isset($min_price) || isset($max_price)){
                    $products = Product::where('available',$available)
                    ->whereBetween('price', [$min_price, $max_price])
                    ->with('category')
                    ->get();
                }else{
                    $products = Product::where('available',$available)
                    ->with('category')
                    ->get();
                }
            }elseif(isset($min_price) && isset($max_price)){
                $products = Product::whereBetween('price', [$min_price, $max_price])
                ->with('category')
                ->get();
            }else{
                $products = Product::where('name','like', '%'."$search" .'%')
                ->orWhere('available',$available)
                ->orWhereBetween('price', [$min_price, $max_price])
                ->orWhereHas('category',function($query) use ($category){
                    $query->where('name','like', '%'."$category" .'%');
                })
                ->with('category')
                ->get();
            }
        }
        else{
            $products = Product::with('category')->get();
        }
        return response()->json($products);
    }

    public function searchAvailabily(Request $request){
        $available = $request->available;
        $search = $request->search;


        if(!is_null($available)){
            $products = Product::where('name','like', '%'."$search" .'%')
            ->where('available',$available)
            ->with('category')
            ->get();
        }else{
            $products = Product::with('category')->get();
        }
        return response()->json($products);
    }

    public function searchMinPrice(Request $request){
        $search = $request->search;
        $available = $request->available;
        $min_price = $request->min_price;
        $max_price = $request->max_price;

        if(!is_null($search) && !is_null($available) || !is_null($max_price) || !is_null($min_price) ){
            $products = Product::where('name','like', '%'."$search" .'%')
            ->where('available',$available)
            ->whereBetween('price', [$min_price, $max_price])
            ->with('category')
            ->get();
        }else{
            $products = Product::with('category')->get();
        }
        return response()->json($products);
    }
}
