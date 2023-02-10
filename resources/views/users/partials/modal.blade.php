<!--Overlay-->
<div class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full" style="background-color: rgba(0,0,0,0.5)" x-show="modal.show">
    <!--Dialog-->

    <div class="bg-white w-11/12 md:max-w-3xl mx-auto rounded shadow-lg py-4 text-left px-6" x-show="modal.show">
        <!--Title-->
        <div class="flex justify-between items-center pb-3">
            <p class="text-2xl font-bold" x-text="modal.action_label"></p>
            <div class="cursor-pointer z-50" @click="modal.show = false">
                <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                    <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                </svg>
            </div>
        </div>
        <div x-show="modal.error.message.length > 0">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <template x-for="error in modal.error.errors">
                    <p x-text="error[0]"></p>
                </template>
            </div>
        </div>
        <!-- content -->
        <div class="flex justify-between">
            <div class="flex-1">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="Name">
                        Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" x-model="modal.user.name" type="text" placeholder="Name">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" x-model="modal.user.email" type="text" placeholder="Email">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" x-model="modal.user.password" type="password" placeholder="Password">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Confirm Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password_confirmation" x-model="modal.user.password_confirmation" type="password" placeholder="Password Confirmation">
                </div>
                <div class="my-6">
                    <div class="dropzone" id="dropzone" x-show="!image.showCroppie"></div>
                    <!--show the cropper-->
                    <div x-show="image.showCroppie">
                        <div class="mx-auto" id="croppie-holder"><img id="img-croppie" class="display-block w-full"></div>
                        <div class="py-2 flex justify-between items-center">
                            <button type="button" class="bg-red-500 text-white p-2 rounded" x-on:click="image.clearPreview()">Delete</button>
                        </div>
                    </div>
                    <input type="hidden" name="points[x1]" x-model="image.point_x1" id="points_x1">
                    <input type="hidden" name="points[y1]" x-model="image.point_y1" id="points_y1">
                    <input type="hidden" name="points[x2]" x-model="image.point_x2" id="points_x2">
                    <input type="hidden" name="points[y2]" x-model="image.point_y2" id="points_y2">
                    <input type="hidden" name="file" x-model="image.path">
                </div>
            </div>
            <div class="flex-1">
                <div id="photo-wrapper" class="flex ml-20 w-1/2 items-center justify-center flex-col p-4 rounded-lg w-48 space-y-4 flex">

                </div>
                
            </div>
        </div>
        <!--Footer-->
        <div class="flex justify-end pt-2">
            <button class="px-4 bg-indigo-500 p-3 rounded-lg text-white hover:bg-indigo-400" x-show="modal.user.id !=''" @click.prevent="update">Update</button>
            <button class="px-4 bg-indigo-500 p-3 rounded-lg text-white hover:bg-indigo-400" x-show="modal.user.id==''" @click.prevent="store">Save</button>
        </div>
    </div>
    <!--/Dialog -->
</div><!-- /Overlay -->