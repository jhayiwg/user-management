<section x-data="photo" class="w-full" x-init="initCroppie($refs.croppie)">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Photo') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile photo.") }}
        </p>
    </header>

    <div class="flex justify-between">

        <div class="grow">
            <div class="active:shadow-sm active:border-blue-500">

                <!--show the input-->
                <form class="dropzone" id="dropzone" x-show="!showCroppie && !hasImage"></form>

                <!--show the cropper-->
                <div x-show="showCroppie">
                    <div class="mx-auto"><img src alt x-ref="croppie" id="img-croppie" class="display-block w-full"></div>
                    <div class="py-2 flex justify-between items-center">
                        <button type="button" class="bg-red-500 text-white p-2 rounded" x-on:click="clearPreview()">Delete</button>
                    </div>
                </div>

            </div>
            <form method="post" action="{{ route('profile.photo.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('patch')
                <input type="hidden" name="points[x1]" x-model="point_x1" id="points_x1">
                <input type="hidden" name="points[y1]" x-model="point_y1" id="points_y1">
                <input type="hidden" name="points[x2]" x-model="point_x2" id="points_x2">
                <input type="hidden" name="points[y2]" x-model="point_y2" id="points_y2">
                <input type="hidden" name="file" x-model="path">

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                    @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
                    @endif
                </div>
            </form>

        </div>
        <div class="w-40">
            <div class="mb-4">
                <img src="{{ $user->photoUrl() }}" class="max-w-full h-40 w-40 h-auto rounded-full" alt="">
            </div>
        </div>
    </div>
</section>