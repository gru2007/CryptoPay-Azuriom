<div class="row g-3">
    <div class="mb-3 col-md-6">
        <label class="form-label" for="keyInput">{{ trans('shop::admin.gateways.private-key') }}</label>
        <input type="text" class="form-control @error('private-key') is-invalid @enderror" id="keyInput" name="private-key" value="{{ old('private-key', $gateway->data['private-key'] ?? '') }}" required>

        @error('private-key')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label class="form-label" for="keyInput">{{ trans('cryptopay::messages.desc') }}</label>
        <input type="text" class="form-control @error('desc') is-invalid @enderror" id="keyInput" name="desc" value="{{ old('desc', $gateway->data['desc'] ?? '') }}" required>

        @error('desc')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label class="form-label" for="keyInput">{{ trans('cryptopay::messages.hidden') }}</label>
        <input type="text" class="form-control @error('hidden') is-invalid @enderror" id="keyInput" name="hidden" value="{{ old('hidden', $gateway->data['hidden'] ?? '') }}" required>

        @error('hidden')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label class="form-label" for="keyInput">{{ trans('cryptopay::messages.color') }}</label>
        <select class="form-control @error('color') is-invalid @enderror" id="keyInput" name="color" value="{{ old('color', $gateway->data['color'] ?? '') }}" required> 
            <option value="1">{{ trans('cryptopay::messages.white') }}</option> 
            <option value="2">{{ trans('cryptopay::messages.black') }}</option> 
        </select>

        @error('color')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>

<div class="alert alert-info">
    <p>
        <i class="bi bi-info-circle"></i>
        @lang('cryptopay::messages.setup', [
            'notification' => '<code>'.route('shop.payments.notification', 'cryptopay').'</code>'
        ])
    </p>

    <a class="btn btn-primary mb-3" target="_blank" href="https://t.me/CryptoBot" role="button" >
        <i class="bi bi-box-arrow-in-right"></i> {{ trans('cryptopay::messages.keys') }}
    </a>
</div>
