<script setup>
import InputError from '@/Components/InputError.vue';
import BlueButton from '@/Components/Buttons/BlueButton.vue';
import { FingerprintSpinner } from 'epic-spinners';
const props = defineProps(['method', 'form', 'text',]);
const showImgs = (input) => {
    if(input.target.files && input.target.files.length)
    {
        var _html = '';
        for(let i = 0; i < input.target.files.length; i++) {
            var file = input.target.files[i]
            var reader = new FileReader();
            reader.onload = function (e) {
                _html += `
                    <a href="${e.target.result}">
                                <img class="w-40 h-40 fill-current object-cover" src="${e.target.result}" alt="">
                    </a>
                `;
               document.querySelector("#my_imgs").innerHTML = _html

            }
            reader.readAsDataURL(file);
        }
    }
}
</script>
<template>
    <form @submit.prevent="props.method" class="w-full">
            <div>
                <textarea name="posts" rows="3" class="border rounded px-2 py-2 w-full" :placeholder="`Post something ${$page.props.auth.user.username} ...`" v-model="props.form.body"></textarea>
            </div>
            <div class="grid gap-4 grid-cols-3" id="my_imgs" v-if="text == 'Post'">
            </div>
            <div class="flex justify-between my-3">
                <div>
                    <InputError :message="props.form.errors.body" class="mt-2" v-if="props.form.errors.body">
                    </InputError>
                </div>
                <div>
                    <BlueButton class="text-xs mr-5" :class="{'opacity-25' : props.form.processing}" :disabled="props.form.processing" v-if="text == 'Post'">
                        <FingerprintSpinner :animation-duration="1500" :size="20" class="text-white" v-if="props.form.processing">
                        </FingerprintSpinner>
                        <div v-else>
                            <label for="img"> Attach <icon class="w-4 h-4 fill-current ml-1" name="files"></icon> </label>
                            <input type="file" id="img" style="display: none;" multiple @input="props.form.imgs = $event.target.files" @change="showImgs"/>
                        </div>                   
                    </BlueButton>
                    <BlueButton type="submit" class="text-xs" :class="{'opacity-25' : props.form.processing}" :disabled="props.form.processing">
                        <FingerprintSpinner :animation-duration="1500" :size="20" class="text-white" v-if="props.form.processing">
                        </FingerprintSpinner>
                        <span v-else>{{ props.text }}</span>
                    </BlueButton>
                </div>
            </div>
    </form>
</template>