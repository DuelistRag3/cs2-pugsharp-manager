<div id="dropdownLogin"
    class="z-10 hidden bg-white rounded-lg shadow-sm w-60 dark:bg-gray-700">
    <div class="p-2 space-y-2 md:space-y-2 sm:p-3">
        <form class="" wire:submit.prevent='login'>
            @if (session()->has('error'))
            <div class="text-red-500 text-sm">
                {{ session('error') }}
            </div>
            @endif
            <div>
                <label for="email" class="block mb-2 text-xs text-gray-900 dark:text-white">{{
                    __('auth.email') }} @error('email') <span class="text-red-500">({{ $message }})</span> @enderror</label>
                <input wire:model='email' type="email" name="email" id="email"
                    class="bg-gray-50 border text-xs border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="name@example.com" required="">
            </div>
            <div>
                <label for="password" class="block mb-2 mt-2 text-xs text-gray-900 dark:text-white">{{
                    __('auth.password') }} @error('password') <span class="text-red-500">({{ $message }})</span> @enderror</label>
                <input wire:model='password' type="password" name="password" id="password" placeholder="••••••••"
                    class="bg-gray-50 border text-xs border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
            </div>
            <div class="flex items-center justify-between mt-2">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input wire:model='remember' id="remember" aria-describedby="remember" type="checkbox"
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800">
                    </div>
                    <div class="ml-3 text-xs">
                        <label for="remember" class="text-gray-500 dark:text-gray-300">{{ __('auth.remember_me')
                            }}</label>
                    </div>
                </div>
            </div>
            <button wire:loading.attr="disabled" type="submit"
                class="w-full text-white disabled:opacity-70! disabled:cursor-not-allowed! bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 mt-2">
                {{ __('auth.login') }}<i class="fa-solid fa-spinner fa-spin ms-2" wire:loading
                    wire:target='login'></i>
            </button>
        </form>
    </div>
    <div class="py-2 w-[90%] mx-auto flex items-center text-sm text-gray-800 before:flex-1 before:border-t before:border-gray-200 before:me-6 after:flex-1 after:border-t after:border-gray-200 after:ms-6 dark:text-white dark:before:border-neutral-600 dark:after:border-neutral-600">or</div>
    <div class="pb-2">
        <a href="#" class="block w-2/3 mx-auto"><img src="https://community.fastly.steamstatic.com/public/images/signinthroughsteam/sits_01.png" alt="Register Image"></a>
    </div>
</div>