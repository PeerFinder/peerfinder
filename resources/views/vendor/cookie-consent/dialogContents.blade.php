<div class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 py-4 bg-white backdrop-blur-sm bg-opacity-30">
    <div class="max-w-4xl mx-auto px-6">
        <div class="p-2 rounded-md bg-pf-darkblue">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                <div class="flex-1">
                    <p class="p-1 sm:px-2 text-white cookie-consent__message">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div>
                    <a class="js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center px-7 py-2 rounded-md text-sm font-medium text-white bg-pf-darkorange hover:bg-pf-midorange">
                        {{ trans('cookie-consent::texts.agree') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
