<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    surname: user.surname,
    direction: user.direction,
    cellphone: user.cellphone,
    sex: user.sex,
    picture: user.picture,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Información del perfil
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                En este apartado puedes actualizar tu nombre y correo electrónico.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 flex gap-7 flex-wrap items-start justify-start  lg:w-[calc(100%+5rem)]">
            <div>
                <InputLabel for="name" value="Nombre" class=":"/>

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                    autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="surname" value="Apellidos" />

                <TextInput id="surname" type="text" class="mt-1 block w-full" v-model="form.surname" required autofocus
                    autocomplete="surname" />

                <InputError class="mt-2" :message="form.errors.surname" />
            </div>

            <div>
                <InputLabel for="direction" value="Dirección" />

                <TextInput id="direction" type="text" class="mt-1 block w-full" v-model="form.direction" required
                    autofocus autocomplete="direction" />

                <InputError class="mt-2" :message="form.errors.direction" />
            </div>

            <div>
                <InputLabel for="cellphone" value="Número de teléfono" />

                <TextInput id="cellphone" type="text" class="mt-1 block w-full" v-model="form.cellphone" required
                    autofocus autocomplete="cellphone" />

                <InputError class="mt-2" :message="form.errors.cellphone" />
            </div>

            <div>
                <InputLabel for="sex" value="Sexo" />

                <select id="sex" name="sex" v-model="form.sex"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600">
                    <option value="" disabled>Seleccione una opción</option>
                    <option value="m">Masculino</option>
                    <option value="f">Femenino</option>
                </select>

                <InputError class="mt-2" :message="form.errors.sex" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Su email no está verificado
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Guardar cambios</PrimaryButton>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                        Cambios guardados correctamente
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
