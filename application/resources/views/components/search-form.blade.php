<div class="d-flex flex-wrap justify-content-end align-items-center">
    <form method="{{ $method }}" class="form-inline" @if($action) action="{{ $action }}" @endif autocomplete="off">
        <div class="input-group justify-content-end">
            <input type="text" name="{{ $name }}" class="form-control bg--white search-color" 
                   placeholder="{{ $placeholder }}" value="{{ $value }}">
            <button class="btn btn--primary input-group-text" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </form>
</div>