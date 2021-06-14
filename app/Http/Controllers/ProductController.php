<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use DB;
use App\Models\ProductImage;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */


  public function index()
    {

        $products = Product::orderBy('id', 'asc')->paginate(5);
        $pvariants = ProductVariant::all();
        $pvprice = ProductVariantPrice::all();

        $colors=DB::table('product_variants')
                ->join('product_variant_prices','product_variants.id','product_variant_prices.product_variant_one')
                ->groupBy('product_variants.variant')
                ->get();

        $sizes=DB::table('product_variants')
        ->join('product_variant_prices','product_variants.id','product_variant_prices.product_variant_two')
        ->groupBy('product_variants.variant')
        ->get();

        return view('products.index',compact('products', 'pvariants', 'pvprice','colors','sizes'));
    }

    public function productSearch(Request $request){
        //$data = $request->all();

        if(!empty($request->title))
        {
            $products = Product::orderBy('id', 'asc')->Where('title','LIKE', "%{$request->title}%")->paginate(5);
            $pvariants = ProductVariant::all();
            $pvprice = ProductVariantPrice::all();
    
            $colors=DB::table('product_variants')
                    ->join('product_variant_prices','product_variants.id','product_variant_prices.product_variant_one')
                    ->groupBy('product_variants.variant')
                    ->get();
    
            $sizes=DB::table('product_variants')
            ->join('product_variant_prices','product_variants.id','product_variant_prices.product_variant_two')
            ->groupBy('product_variants.variant')
            ->get();
            return view('products.index',compact('products', 'pvariants', 'pvprice','colors','sizes'));
        }

        if(!empty($request->price_from))
        {
            
            $pvprice = ProductVariantPrice::whereBetween('price',[$request->price_from, $request->price_to])->get();
            
            foreach ($pvprice as $pc) {
                $pvariants = ProductVariant::where('id',$pc->product_variant_one)->where('product_id',$pc->product_id)->get();
                $pvariants = ProductVariant::where('id',$pc->product_variant_two)->where('product_id',$pc->product_id)->get();
                $products = Product::where('id',$pc->product_id)->paginate(5);
            }

            $colors=DB::table('product_variants')
                    ->join('product_variant_prices','product_variants.id','product_variant_prices.product_variant_one')
                    ->groupBy('product_variants.variant')
                    ->get();

            $sizes=DB::table('product_variants')
            ->join('product_variant_prices','product_variants.id','product_variant_prices.product_variant_two')
            ->groupBy('product_variants.variant')
            ->get();
            return view('products.index',compact('products', 'pvariants', 'pvprice','colors','sizes'));

        }

        if(!empty($request->variant))
        {
            
            $pvariants = ProductVariant::where('id',$request->variant)->get();
            
            foreach ($pvariants as $pvc) {
                $pvprice = ProductVariantPrice::where('product_variant_one',$pvc->id)->where('product_id',$pvc->product_id)->get();
                $pvprice = ProductVariantPrice::where('product_variant_two',$pvc->id)->where('product_id',$pvc->product_id)->get();
                $products = Product::where('id',$pvc->product_id)->paginate(5);
            }

            $colors=DB::table('product_variants')
                    ->join('product_variant_prices','product_variants.id','product_variant_prices.product_variant_one')
                    ->groupBy('product_variants.variant')
                    ->get();

            $sizes=DB::table('product_variants')
            ->join('product_variant_prices','product_variants.id','product_variant_prices.product_variant_two')
            ->groupBy('product_variants.variant')
            ->get();
            return view('products.index',compact('products', 'pvariants', 'pvprice','colors','sizes'));

        }

        if(!empty($request->date))
        {
            $products = Product::orderBy('id', 'asc')->Where('created_at','>=', date('Y-m-d', strtotime($request->date)).' 00:00:00')->paginate(5);
            $pvariants = ProductVariant::all();
            $pvprice = ProductVariantPrice::all();
    
            $colors=DB::table('product_variants')
                    ->join('product_variant_prices','product_variants.id','product_variant_prices.product_variant_one')
                    ->groupBy('product_variants.variant')
                    ->get();
    
            $sizes=DB::table('product_variants')
            ->join('product_variant_prices','product_variants.id','product_variant_prices.product_variant_two')
            ->groupBy('product_variants.variant')
            ->get();
            return view('products.index',compact('products', 'pvariants', 'pvprice','colors','sizes'));
        }

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //$data = $request->all();
        //dd($data);
        $data=array();
        $data['title']=$request->title;
        $data['sku']=$request->sku;
        $data['description']=$request->description;
        $product_id=DB::table('products')->insertGetId($data);

        foreach ($request->product_variant as $pvc) {

            if($pvc['option'] == '1'){
                foreach($pvc['tags'] as $pv){
                    $pvarr=array();
                    $pvarr['variant']=$pv;
                    $pvarr['variant_id']='1';
                    $pvarr['product_id']=$product_id;
                    $v_one = DB::table('product_variants')->insertGetId($pvarr);
                }
            }
            if($pvc['option'] == '2'){
                foreach($pvc['tags'] as $pv){
                    $pvarr=array();
                    $pvarr['variant']=$pv;
                    $pvarr['variant_id']='2';
                    $pvarr['product_id']=$product_id;
                    $v_two = DB::table('product_variants')->insertGetId($pvarr);
                }
            }
       
            if($pvc['option'] == '6'){
                foreach($pvc['tags'] as $pv){
                    $pvarr=array();
                    $pvarr['variant']=$pv;
                    $pvarr['variant_id']='6';
                    $pvarr['product_id']=$product_id;
                    $v_three = DB::table('product_variants')->insertGetId($pvarr);
                }
            }

        } 

        // How can check title---???
        foreach ($request->product_variant_prices as $prc) {
            $pricevariants=array();
            $pricevariants['product_variant_one']=$v_one;
            $pricevariants['product_variant_two']=$v_two;
            $pricevariants['product_variant_three']=$v_three;
            $pricevariants['price']=$prc['price'];
            $pricevariants['stock']=$prc['stock'];
            $pricevariants['product_id']=$product_id;
            DB::table('product_variant_prices')->insert($pricevariants);
        }      

    }


    public function show($id)
    {
        $product=DB::table('products')->where('id',$id)->first();
        return response()->json($product);
    }

    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
