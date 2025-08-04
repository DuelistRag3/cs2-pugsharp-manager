<div class="grid grid-cols-1 xl:grid-cols-8 gap-2 mb-4 max-w-10/12 md:max-w-6/12 mx-auto">
    <div class="xl:col-span-3">
        <div
            class="w-full max-h-max bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-end px-4 pt-4">
            </div>
            <div class="flex flex-col items-center pb-5">
                <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $team->logoUrl() }}" alt="Bonnie image" />
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $team->name }}</h5>

            </div>
        </div>
    </div>
</div>