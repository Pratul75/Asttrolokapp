<section class="mt-30">
    <h2 class="section-title after-line">{{ trans('public.zoom_api') }}</h2>

    <div class="row mt-20">
        <div class="col-12 col-lg-4">

            <div class="form-group">
                <label class="input-label">{{ trans('update.zoom_api_key') }}</label>
                <textarea type="text" name="zoom_api_key" rows="3" class="form-control">{{ (!empty($user) and empty($new_user) and $user->zoomApi) ? $user->zoomApi->api_key : old('api_key') }}</textarea>

                <p class="font-12 text-gray mt-5"><a href="https://community.zoom.com/t5/Marketplace/How-do-I-get-API-Key-amp-API-Secret/td-p/28307">{{ trans('update.zoom_api_key_hint') }}</a></p>
            </div>

            <div class="form-group">
                <label class="input-label">{{ trans('update.zoom_api_secret') }}</label>
                <textarea type="text" name="zoom_api_secret" rows="4" class="form-control">{{ (!empty($user) and empty($new_user) and $user->zoomApi) ? $user->zoomApi->api_secret : old('api_secret') }}</textarea>

                <p class="font-12 text-gray mt-5"><a href="https://community.zoom.com/t5/Marketplace/How-do-I-get-API-Key-amp-API-Secret/td-p/28307">{{ trans('update.zoom_api_secret_hint') }}</a></p>
            </div>

        </div>
    </div>


</section>
