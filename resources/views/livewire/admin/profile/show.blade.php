

<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 w-2xl">
    <a href="#">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Bearer Token</h5>
    </a>
    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Your Token: {{ Auth::user()->tokens->first()->token }}</p>
    @if(Auth::user()->tokens->isEmpty())
    <button wire:click="createToken" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 cursor-pointer">
        Create a Token
    </button>
    @endif
</div>
