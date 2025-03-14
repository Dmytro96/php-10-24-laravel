<div class="col">
    <div class="card shadow-sm product-card" style="width: 18rem; position: relative">
        {{--        @if($product->withDiscount)--}}
        {{--            <span class="badge rounded-pill text-bg-danger discount-badge">--}}
        {{--                {{$product->discount}}%--}}
        {{--            </span>--}}
        {{--        @endif--}}
        <img src="{{ $product->thumbnailUrl }}" class="card-img-top w-100 product-card-image"
             alt="{{ $product->title }}">
        <div class="card-body">
            <h5 class="card-title">{{ $product->title }}</h5>
            {{--            @if($product->withDiscount)--}}
            {{--                <div class="row" style="color: rgba(107,29,29,0.89)">--}}
            {{--                    <small class="col-12 col-sm-6">Old Price: </small>--}}
            {{--                    <small class="col-12 col-sm-6 text-decoration-line-through">{{ $product->price }} $</small>--}}
            {{--                </div>--}}
            {{--            @endif--}}
            {{--            @if($product->isSimple)--}}
            <div class="row">
                <div class="col-12 col-sm-6">Price:</div>
                <div class="col-12 col-sm-6">{{ $product->finalPrice }} $</div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-6">Price:</div>
                <div class="col-12 col-sm-6">{{ $product->finalPrice }} $</div>
            </div>

        </div>
        <div class="card-footer">
            <div class="btn-group btn-group-sm gap-1 w-100 d-flex align-items-center justify-content-between"
                 role="group">
                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info my-2">Show</a>
                <button class="btn btn-outline-success product-card-buy" data-action="{{ route('ajax.cart.add', $product) }}">Buy</button>
            </div>
        </div>
    </div>
</div>
