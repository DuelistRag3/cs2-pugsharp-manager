<div id="dropdownRegister"
    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-60 dark:bg-gray-700">
    <div class="p-2 space-y-2 md:space-y-2 sm:p-3">
        <form class="" wire:submit.prevent='register'>
            @if (session()->has('error'))
            <div class="text-red-500 text-sm">
                {{ session('error') }}
            </div>
            @endif
            <div class="mt-2">
                <label for="username" class="block mb-2 text-xs text-gray-900 dark:text-white">{{
                    __('auth.username') }} @error('username') <span class="text-red-500">({{ $message }})</span> @enderror</label>
                <input wire:model='username' type="text" name="username" id="username"
                    class="bg-gray-50 border text-xs border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="{{ __('auth.username') }}" required="">
            </div>
            <div class="mt-2">
                <label for="email" class="block mb-2 text-xs text-gray-900 dark:text-white">{{
                    __('auth.email') }} @error('email') <span class="text-red-500">({{ $message }})</span> @enderror</label>
                <input wire:model='email' type="email" name="email" id="email"
                    class="bg-gray-50 border text-xs border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="name@example.com" required="">
            </div>
            <div class="mt-2">
                <label for="password" class="block mb-2 text-xs text-gray-900 dark:text-white">{{
                    __('auth.password') }} @error('password') <span class="text-red-500">({{ $message }})</span> @enderror</label>
                <input wire:model='password' type="password" name="password" id="password" placeholder="••••••••"
                    class="bg-gray-50 border text-xs border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required="">
            </div>
            <div class="mt-2">
                <label for="password_confirmation" class="block mb-2 text-xs text-gray-900 dark:text-white">{{
                    __('auth.password_confirmation') }} @error('password_confirmation') <span class="text-red-500">({{ $message }})</span> @enderror</label>
                <input wire:model='password_confirmation' type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••"
                    class="bg-gray-50 border text-xs border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required="">
            </div>
            <button wire:loading.attr="disabled" type="submit"
                class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 mt-2">
                {{ __('auth.register') }}
            </button>
        </form>
    </div>
</div>